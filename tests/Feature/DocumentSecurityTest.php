<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Customer;
use App\Models\District;
use App\Models\Document;
use App\Models\Hotel;
use App\Models\PoliceStation;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\User;
use App\Support\DocumentUploader;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Phase 7 security pass: confirms the fix for a real gap found while reviewing this
 * checklist item — `documents` table files used to live on the `public` disk (served
 * directly by the webserver, no auth), which technically satisfied "hard to guess"
 * but not "not publicly reachable without auth." Now on the protected `local` disk,
 * served only through Admin\DocumentController, which re-checks permission and tenant/
 * jurisdiction scope on every request. Also covers the audit_logs trail (PRD § 6).
 */
class DocumentSecurityTest extends TestCase
{
    use RefreshDatabase;

    private Hotel $hotelA;
    private Hotel $hotelB;
    private Customer $customerA;
    private Document $customerADoc;
    private Document $hotelADoc;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake(DocumentUploader::DISK);

        $this->hotelA = Hotel::withoutGlobalScopes()->where('slug', 'hotel-beach-way')->firstOrFail();

        $dhaka = District::where('name', 'Dhaka')->firstOrFail();
        $this->hotelB = Hotel::create([
            'name' => 'Doc Test Hotel B', 'slug' => 'doc-test-hotel-b',
            'mobile' => '01700000000', 'address' => 'Dhaka',
            'district_id' => $dhaka->id,
            'police_station_id' => PoliceStation::where('district_id', $dhaka->id)->firstOrFail()->id,
            'status' => 'active',
        ]);

        $this->customerA = Customer::create(['hotel_id' => $this->hotelA->id, 'name' => 'Doc Guest A', 'phone' => '01711110000']);

        Storage::disk(DocumentUploader::DISK)->put('customers/documents/fake-nid.jpg', 'fake-image-bytes');
        $this->customerADoc = Document::create([
            'documentable_type' => Customer::class, 'documentable_id' => $this->customerA->id,
            'category' => 'nid_front', 'file_path' => 'customers/documents/fake-nid.jpg', 'original_filename' => 'nid.jpg',
        ]);

        Storage::disk(DocumentUploader::DISK)->put('hotels/documents/fake-license.pdf', 'fake-pdf-bytes');
        $this->hotelADoc = Document::create([
            'documentable_type' => Hotel::class, 'documentable_id' => $this->hotelA->id,
            'category' => 'trade_license', 'file_path' => 'hotels/documents/fake-license.pdf', 'original_filename' => 'license.pdf',
        ]);
    }

    private function hotelStaff(Hotel $hotel, array $modulePerms = ['customers']): User
    {
        $role = Role::create(['name' => 'Staff', 'slug' => 'staff-' . uniqid(), 'is_super_admin' => false, 'is_active' => true, 'scope_type' => 'hotel']);
        foreach ($modulePerms as $m) {
            RolePermission::create(['role_id' => $role->id, 'module_key' => $m, 'can_view' => true]);
        }
        return User::create(['name' => 'Staff', 'email' => uniqid() . '@t.local', 'password' => 'password', 'role_id' => $role->id, 'hotel_id' => $hotel->id, 'is_active' => true]);
    }

    private function dcUser(int $districtId): User
    {
        $role = Role::create(['name' => 'DC', 'slug' => 'dc-' . uniqid(), 'is_super_admin' => false, 'is_active' => true, 'scope_type' => 'district']);
        foreach (['customers', 'gov-reports', 'dashboard'] as $m) {
            RolePermission::create(['role_id' => $role->id, 'module_key' => $m, 'can_view' => true]);
        }
        return User::create(['name' => 'DC', 'email' => uniqid() . '@t.local', 'password' => 'password', 'role_id' => $role->id, 'district_id' => $districtId, 'is_active' => true]);
    }

    public function test_unauthenticated_request_is_redirected_to_login(): void
    {
        $this->get(route('admin.documents.show', $this->customerADoc))
            ->assertRedirect(route('login'));
    }

    public function test_own_hotel_staff_can_view_the_document(): void
    {
        $this->actingAs($this->hotelStaff($this->hotelA))
            ->get(route('admin.documents.show', $this->customerADoc))
            ->assertOk();
    }

    public function test_other_hotel_staff_cannot_view_the_document(): void
    {
        $this->actingAs($this->hotelStaff($this->hotelB))
            ->get(route('admin.documents.show', $this->customerADoc))
            ->assertNotFound();
    }

    public function test_in_jurisdiction_government_user_can_view_the_document(): void
    {
        $this->actingAs($this->dcUser($this->hotelA->district_id))
            ->get(route('admin.documents.show', $this->customerADoc))
            ->assertOk();
    }

    public function test_out_of_jurisdiction_government_user_cannot_view_the_document(): void
    {
        $this->actingAs($this->dcUser($this->hotelB->district_id))
            ->get(route('admin.documents.show', $this->customerADoc))
            ->assertNotFound();
    }

    public function test_hotel_document_requires_hotel_registration_permission(): void
    {
        // Hotel-scoped staff without hotel-registration permission — blocked, even for
        // their own hotel's document (hotel legal docs are a platform-admin concern).
        $this->actingAs($this->hotelStaff($this->hotelA))
            ->get(route('admin.documents.show', $this->hotelADoc))
            ->assertForbidden();

        $super = User::create(['name' => 'Super', 'email' => uniqid() . '@t.local', 'password' => 'password', 'role_id' => null, 'is_active' => true]);
        $this->actingAs($super)
            ->get(route('admin.documents.show', $this->hotelADoc))
            ->assertOk();
    }

    public function test_viewing_a_guest_as_government_user_is_audited_but_routine_staff_access_is_not(): void
    {
        $dc = $this->dcUser($this->hotelA->district_id);
        $this->actingAs($dc)->get(route('admin.customers.show', $this->customerA));

        $this->assertSame(1, AuditLog::where('action', 'viewed_guest')->where('user_id', $dc->id)->count());

        $staff = $this->hotelStaff($this->hotelA);
        $before = AuditLog::count();
        $this->actingAs($staff)->get(route('admin.customers.show', $this->customerA));
        $this->assertSame($before, AuditLog::count());
    }

    public function test_nid_search_and_report_export_are_audited(): void
    {
        $dc = $this->dcUser($this->hotelA->district_id);

        $this->actingAs($dc)->get(route('admin.government.nid-search', ['nid' => '123']));
        $this->assertSame(1, AuditLog::where('action', 'searched_nid')->count());

        $this->actingAs($dc)->get(route('admin.government.export.csv', ['type' => 'hotels']));
        $log = AuditLog::where('action', 'exported_report')->first();
        $this->assertNotNull($log);
        $this->assertSame('hotels', $log->meta['type']);
        $this->assertSame('csv', $log->meta['format']);
    }
}
