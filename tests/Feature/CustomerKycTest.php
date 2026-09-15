<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Document;
use App\Models\Hotel;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\User;
use App\Support\DocumentUploader;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Phase 5 — guest KYC expansion. Exercises Admin\CustomerController's new edit/update/
 * document endpoints and, importantly, the police-only is_flagged/flagged_note gating
 * (PRD § 5.4), which per docs/hgrm-saas must be enforced server-side, not just hidden
 * in the UI — this test proves the field is actually stripped from the response and
 * rejected on write for non-police roles, not merely absent from what the Vue page
 * chooses to render.
 */
class CustomerKycTest extends TestCase
{
    use RefreshDatabase;

    private Hotel $hotel;
    private User $hotelStaff;
    private User $policeUser;
    private Customer $customer;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        Storage::fake(DocumentUploader::DISK);

        $this->hotel = Hotel::withoutGlobalScopes()->where('slug', 'hotel-beach-way')->firstOrFail();

        $staffRole = Role::create(['name' => 'Front Desk', 'slug' => 'front-desk-' . uniqid(), 'is_super_admin' => false, 'is_active' => true, 'scope_type' => 'hotel']);
        RolePermission::create(['role_id' => $staffRole->id, 'module_key' => 'customers', 'can_view' => true, 'can_create' => false, 'can_edit' => true, 'can_delete' => true]);
        $this->hotelStaff = User::create(['name' => 'Front Desk', 'email' => uniqid() . '@test.local', 'password' => 'password', 'role_id' => $staffRole->id, 'hotel_id' => $this->hotel->id, 'is_active' => true]);

        $policeRole = Role::create(['name' => 'Police Admin', 'slug' => 'police-' . uniqid(), 'is_super_admin' => false, 'is_active' => true, 'scope_type' => 'police_station']);
        RolePermission::create(['role_id' => $policeRole->id, 'module_key' => 'customers', 'can_view' => true, 'can_create' => false, 'can_edit' => true, 'can_delete' => true]);
        $this->policeUser = User::create(['name' => 'Police Officer', 'email' => uniqid() . '@test.local', 'password' => 'password', 'role_id' => $policeRole->id, 'police_station_id' => $this->hotel->police_station_id, 'is_active' => true]);

        $this->customer = Customer::create(['hotel_id' => $this->hotel->id, 'name' => 'Guest One', 'phone' => '01711112222']);
    }

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Guest One Updated',
            'phone' => '01711112222',
            'document_type' => 'nid',
            'is_foreign_guest' => false,
            'is_couple' => false,
        ], $overrides);
    }

    public function test_hotel_staff_can_open_and_update_a_guest_record(): void
    {
        $this->actingAs($this->hotelStaff)->get(route('admin.customers.edit', $this->customer))->assertOk();

        $this->actingAs($this->hotelStaff)
            ->post(route('admin.customers.update', $this->customer), $this->validPayload([
                'father_name' => 'Father Name', 'gender' => 'male', 'occupation' => 'Engineer',
            ]))
            ->assertRedirect(route('admin.customers.show', $this->customer));

        $this->customer->refresh();
        $this->assertSame('Guest One Updated', $this->customer->name);
        $this->assertSame('Father Name', $this->customer->father_name);
        $this->assertSame('male', $this->customer->gender);
    }

    /**
     * Regression test for a real bug found while manually verifying this feature over
     * real HTTP: the actual Vue form submits via Inertia's forceFormData (required
     * for file uploads), which serializes JS checkbox booleans through FormData as
     * the literal strings "true"/"false" — not the "1"/"0" or native-bool shapes the
     * other tests in this file use. Laravel's `boolean` validation rule actually
     * REJECTS the string "false" outright, which silently failed every update
     * whenever a checkbox was left unchecked. Uses raw string values specifically to
     * reproduce the real submission shape, not PHP-native booleans.
     */
    public function test_update_succeeds_with_string_boolean_values_matching_real_form_submissions(): void
    {
        $this->actingAs($this->hotelStaff)
            ->post(route('admin.customers.update', $this->customer), [
                'name' => 'Guest One', 'phone' => '01711112222', 'document_type' => 'nid',
                'is_foreign_guest' => 'false', 'is_couple' => 'false',
                'father_name' => 'Father Name',
            ])
            ->assertRedirect(route('admin.customers.show', $this->customer));

        $this->assertSame('Father Name', $this->customer->fresh()->father_name);

        $this->actingAs($this->hotelStaff)
            ->post(route('admin.customers.update', $this->customer), [
                'name' => 'Guest One', 'phone' => '01711112222', 'document_type' => 'nid',
                'is_foreign_guest' => 'true', 'is_couple' => 'false',
                'visa_number' => 'V-999', 'arrival_date_bd' => '2026-07-01',
            ])
            ->assertRedirect(route('admin.customers.show', $this->customer));

        $fresh = $this->customer->fresh();
        $this->assertTrue($fresh->is_foreign_guest);
        $this->assertSame('V-999', $fresh->visa_number);
    }

    public function test_foreign_guest_fields_are_required_when_flagged_as_foreign(): void
    {
        $this->actingAs($this->hotelStaff)
            ->post(route('admin.customers.update', $this->customer), $this->validPayload(['is_foreign_guest' => true]))
            ->assertSessionHasErrors(['visa_number', 'arrival_date_bd']);

        $this->actingAs($this->hotelStaff)
            ->post(route('admin.customers.update', $this->customer), $this->validPayload([
                'is_foreign_guest' => true, 'visa_number' => 'V-123', 'arrival_date_bd' => '2026-07-01',
            ]))
            ->assertSessionDoesntHaveErrors(['visa_number', 'arrival_date_bd']);
    }

    public function test_spouse_name_is_required_when_couple_is_checked(): void
    {
        $this->actingAs($this->hotelStaff)
            ->post(route('admin.customers.update', $this->customer), $this->validPayload(['is_couple' => true]))
            ->assertSessionHasErrors(['spouse_name']);

        $this->actingAs($this->hotelStaff)
            ->post(route('admin.customers.update', $this->customer), $this->validPayload([
                'is_couple' => true, 'spouse_name' => 'Spouse Name',
            ]))
            ->assertSessionDoesntHaveErrors(['spouse_name']);

        $this->assertSame('Spouse Name', $this->customer->fresh()->spouse_name);
    }

    public function test_document_uploads_are_stored_with_correct_categories(): void
    {
        $this->actingAs($this->hotelStaff)->post(route('admin.customers.update', $this->customer), $this->validPayload([
            'nid_front_doc' => UploadedFile::fake()->image('nid-front.jpg'),
            'nid_back_doc'  => UploadedFile::fake()->image('nid-back.jpg'),
            'guest_photo_doc' => UploadedFile::fake()->image('photo.jpg'),
            'other_documents' => [UploadedFile::fake()->create('extra.pdf', 50, 'application/pdf')],
        ]));

        $this->assertSame(1, Document::where('documentable_id', $this->customer->id)->where('category', 'nid_front')->count());
        $this->assertSame(1, Document::where('documentable_id', $this->customer->id)->where('category', 'nid_back')->count());
        $this->assertSame(1, Document::where('documentable_id', $this->customer->id)->where('category', 'guest_photo')->count());
        $this->assertSame(1, Document::where('documentable_id', $this->customer->id)->where('category', 'other')->count());
    }

    public function test_hotel_staff_cannot_see_or_set_the_police_flag(): void
    {
        $this->actingAs($this->hotelStaff)
            ->get(route('admin.customers.edit', $this->customer))
            ->assertInertia(fn ($page) => $page
                ->where('canManageFlag', false)
                ->missing('customer.is_flagged')
                ->missing('customer.flagged_note')
            );

        // Even if a hotel-staff request maliciously includes is_flagged, it must not apply.
        $this->actingAs($this->hotelStaff)->post(route('admin.customers.update', $this->customer), $this->validPayload([
            'is_flagged' => true, 'flagged_note' => 'should not be saved',
        ]));

        $this->assertFalse($this->customer->fresh()->is_flagged);
        $this->assertNull($this->customer->fresh()->flagged_note);
    }

    public function test_police_role_can_see_and_set_the_flag(): void
    {
        $this->actingAs($this->policeUser)
            ->get(route('admin.customers.edit', $this->customer))
            ->assertInertia(fn ($page) => $page
                ->where('canManageFlag', true)
                ->has('customer.is_flagged')
            );

        $this->actingAs($this->policeUser)->post(route('admin.customers.update', $this->customer), $this->validPayload([
            'is_flagged' => true, 'flagged_note' => 'Reported by local station',
        ]));

        $fresh = $this->customer->fresh();
        $this->assertTrue($fresh->is_flagged);
        $this->assertSame('Reported by local station', $fresh->flagged_note);
    }

    public function test_flag_is_hidden_from_index_and_show_for_non_police_roles(): void
    {
        $this->customer->update(['is_flagged' => true, 'flagged_note' => 'secret']);

        $this->actingAs($this->hotelStaff)
            ->get(route('admin.customers.index'))
            ->assertInertia(fn ($page) => $page
                ->where('canManageFlag', false)
                ->missing('customers.0.is_flagged')
            );

        $this->actingAs($this->hotelStaff)
            ->get(route('admin.customers.show', $this->customer))
            ->assertInertia(fn ($page) => $page->missing('customer.flagged_note'));
    }
}
