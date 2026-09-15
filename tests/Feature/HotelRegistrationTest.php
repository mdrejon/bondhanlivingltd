<?php

namespace Tests\Feature;

use App\Models\District;
use App\Models\Document;
use App\Models\Hotel;
use App\Models\PoliceStation;
use App\Models\Role;
use App\Models\User;
use App\Support\DocumentUploader;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Exercises the real HTTP stack for Phase 4 (Admin\HotelController + the
 * hotel_id assignment field on UserController) — routes, middleware, validation,
 * file storage, and the tenant scope from Phase 2/3 — end to end. No headless
 * browser was available in this environment to click through the Vue UI, so this
 * is the rigorous substitute for the backend half of verification; see
 * docs/hgrm-saas/WORKFLOW-ROADMAP.md Phase 4 for the still-open manual UI check.
 */
class HotelRegistrationTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;
    private District $dhaka;
    private int $dhakaPoliceStationId;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        Storage::fake(DocumentUploader::DISK);

        $superAdminRole = Role::create([
            'name' => 'Super Admin', 'slug' => 'super-admin-' . uniqid(),
            'is_super_admin' => true, 'is_active' => true,
        ]);
        $this->superAdmin = User::create([
            'name' => 'Super Admin', 'email' => uniqid() . '@test.local',
            'password' => 'password', 'role_id' => $superAdminRole->id, 'is_active' => true,
        ]);

        $this->dhaka = District::where('name', 'Dhaka')->firstOrFail();
        $this->dhakaPoliceStationId = PoliceStation::where('district_id', $this->dhaka->id)->firstOrFail()->id;
    }

    private function validHotelPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Dhaka Grand Hotel',
            'category' => '4 Star',
            'total_rooms' => 40,
            'mobile' => '01711111111',
            'address' => 'Gulshan, Dhaka',
            'district_id' => $this->dhaka->id,
            'police_station_id' => $this->dhakaPoliceStationId,
        ], $overrides);
    }

    /** For fixtures created directly via Eloquent (bypassing the controller, which
     *  is what normally generates the slug) — form-submission tests use
     *  validHotelPayload() as-is, matching what the real create/edit form posts. */
    private function directCreateHotel(array $overrides = []): Hotel
    {
        return Hotel::create($this->validHotelPayload(array_merge(['slug' => 'dhaka-grand-hotel-' . uniqid()], $overrides)));
    }

    public function test_super_admin_can_view_hotel_pages(): void
    {
        $this->actingAs($this->superAdmin)->get(route('admin.hotels.index'))->assertOk();
        $this->actingAs($this->superAdmin)->get(route('admin.hotels.create'))->assertOk();
    }

    public function test_non_super_admin_without_permission_is_blocked(): void
    {
        $role = Role::create(['name' => 'Front Desk', 'slug' => 'front-desk-' . uniqid(), 'is_super_admin' => false, 'is_active' => true]);
        $user = User::create(['name' => 'Staff', 'email' => uniqid() . '@test.local', 'password' => 'password', 'role_id' => $role->id, 'is_active' => true]);

        $this->actingAs($user)->get(route('admin.hotels.index'))->assertRedirect(route('admin.dashboard'));
    }

    public function test_store_creates_a_hotel_with_logo_and_documents(): void
    {
        $logo = UploadedFile::fake()->image('logo.jpg');
        $license = UploadedFile::fake()->create('trade_license.pdf', 100, 'application/pdf');
        $photo1 = UploadedFile::fake()->image('photo1.jpg');
        $photo2 = UploadedFile::fake()->image('photo2.jpg');

        $response = $this->actingAs($this->superAdmin)->post(route('admin.hotels.store'), $this->validHotelPayload([
            'logo' => $logo,
            'trade_license_doc' => $license,
            'photos' => [$photo1, $photo2],
        ]));

        $hotel = Hotel::where('name', 'Dhaka Grand Hotel')->first();
        $this->assertNotNull($hotel);
        $response->assertRedirect(route('admin.hotels.show', $hotel));

        $this->assertSame('active', $hotel->status);
        $this->assertSame('dhaka-grand-hotel', $hotel->slug);
        $this->assertFalse($hotel->is_primary_site);
        $this->assertNotNull($hotel->logo);
        Storage::disk('public')->assertExists($hotel->logo);

        $this->assertSame(1, Document::where('documentable_id', $hotel->id)->where('category', 'trade_license')->count());
        $this->assertSame(2, Document::where('documentable_id', $hotel->id)->where('category', 'hotel_photo')->count());
    }

    public function test_duplicate_trade_license_number_is_rejected(): void
    {
        $this->directCreateHotel(['name' => 'Existing Hotel', 'trade_license_no' => 'TL-123']);

        $this->actingAs($this->superAdmin)
            ->post(route('admin.hotels.store'), $this->validHotelPayload(['trade_license_no' => 'TL-123']))
            ->assertSessionHasErrors('trade_license_no');
    }

    public function test_update_status_changes_hotel_status(): void
    {
        $hotel = $this->directCreateHotel();

        $this->actingAs($this->superAdmin)
            ->patch(route('admin.hotels.update-status', $hotel), ['status' => 'suspended'])
            ->assertRedirect();

        $this->assertSame('suspended', $hotel->fresh()->status);
    }

    public function test_replacing_a_document_deletes_the_old_file(): void
    {
        $hotel = $this->directCreateHotel();
        $first = UploadedFile::fake()->create('license-v1.pdf', 50, 'application/pdf');

        $this->actingAs($this->superAdmin)->post(route('admin.hotels.update', $hotel), array_merge(
            $this->validHotelPayload(), ['trade_license_doc' => $first]
        ));

        $firstDoc = Document::where('documentable_id', $hotel->id)->where('category', 'trade_license')->first();
        $firstPath = $firstDoc->file_path;
        Storage::disk(DocumentUploader::DISK)->assertExists($firstPath);

        $second = UploadedFile::fake()->create('license-v2.pdf', 50, 'application/pdf');
        $this->actingAs($this->superAdmin)->post(route('admin.hotels.update', $hotel), array_merge(
            $this->validHotelPayload(), ['trade_license_doc' => $second]
        ));

        Storage::disk(DocumentUploader::DISK)->assertMissing($firstPath);
        $this->assertSame(1, Document::where('documentable_id', $hotel->id)->where('category', 'trade_license')->count());
    }

    public function test_assigning_a_new_user_to_the_new_hotel_isolates_them_from_other_hotels(): void
    {
        $hbw = Hotel::withoutGlobalScopes()->where('slug', 'hotel-beach-way')->firstOrFail();
        $dhakaHotel = $this->directCreateHotel();

        $staffRole = Role::create(['name' => 'Hotel Staff', 'slug' => 'hotel-staff-' . uniqid(), 'is_super_admin' => false, 'is_active' => true]);

        // Real end-to-end path: create the user through the actual Users controller
        // (Phase 4's hotel_id field), the way an admin would in the browser.
        $this->actingAs($this->superAdmin)->post(route('admin.users.store'), [
            'name' => 'Dhaka Hotel Admin',
            'email' => 'dhaka-admin@test.local',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role_id' => $staffRole->id,
            'hotel_id' => $dhakaHotel->id,
            'is_active' => true,
        ])->assertRedirect(route('admin.users.index'));

        $dhakaAdmin = User::where('email', 'dhaka-admin@test.local')->firstOrFail();
        $this->assertSame($dhakaHotel->id, $dhakaAdmin->hotel_id);

        \App\Models\RoomType::create(['hotel_id' => $hbw->id, 'name' => 'HBW Room', 'slug' => 'hbw-room-' . uniqid()]);
        \App\Models\RoomType::create(['hotel_id' => $dhakaHotel->id, 'name' => 'Dhaka Room', 'slug' => 'dhaka-room-' . uniqid()]);

        $this->actingAs($dhakaAdmin);
        $this->assertSame(1, \App\Models\RoomType::count());
        $this->assertSame($dhakaHotel->id, \App\Models\RoomType::first()->hotel_id);
    }
}
