<?php

namespace Tests\Feature;

use App\Models\Backup;
use App\Models\District;
use App\Models\Hotel;
use App\Models\PoliceStation;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\RoomType;
use App\Models\User;
use App\Services\HotelDataBackupService;
use App\Support\PublicHotelContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Phase 8B — per-hotel export/import. The core claims under test: a hotel's
 * export/restore never touches another hotel's data, a backup file is refused
 * when restored against a different hotel (or when it's the whole-database .sql
 * format), and a restore is blocked when the hotel has data newer than the backup
 * unless explicitly forced — the restore-semantics decision from the Phase 8B plan.
 */
class HotelBackupTest extends TestCase
{
    use RefreshDatabase;

    private Hotel $hotelA;
    private Hotel $hotelB;
    private User $adminA;
    private User $adminB;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');

        $district = District::first();
        $policeStation = PoliceStation::where('district_id', $district->id)->firstOrFail();

        $this->hotelA = Hotel::withoutGlobalScopes()->create([
            'name' => 'Backup Test Hotel A', 'slug' => 'backup-test-hotel-a',
            'mobile' => '01700000010', 'address' => 'A', 'district_id' => $district->id, 'police_station_id' => $policeStation->id, 'status' => 'active',
        ]);
        $this->hotelB = Hotel::withoutGlobalScopes()->create([
            'name' => 'Backup Test Hotel B', 'slug' => 'backup-test-hotel-b',
            'mobile' => '01700000011', 'address' => 'B', 'district_id' => $district->id, 'police_station_id' => $policeStation->id, 'status' => 'active',
        ]);

        $role = Role::create(['name' => 'Backup Tester', 'slug' => 'backup-tester-' . uniqid(), 'is_super_admin' => false, 'is_active' => true, 'scope_type' => 'hotel']);
        RolePermission::create(['role_id' => $role->id, 'module_key' => 'hotel-backups', 'can_view' => true, 'can_create' => true, 'can_delete' => true]);

        $this->adminA = User::create(['name' => 'Admin A', 'email' => uniqid() . '@t.local', 'password' => 'password', 'role_id' => $role->id, 'hotel_id' => $this->hotelA->id, 'is_active' => true]);
        $this->adminB = User::create(['name' => 'Admin B', 'email' => uniqid() . '@t.local', 'password' => 'password', 'role_id' => $role->id, 'hotel_id' => $this->hotelB->id, 'is_active' => true]);
    }

    private function makeRoomType(Hotel $hotel, string $name): RoomType
    {
        PublicHotelContext::set($hotel);
        $rt = RoomType::create(['hotel_id' => $hotel->id, 'name' => $name, 'slug' => \Illuminate\Support\Str::slug($name) . '-' . uniqid(), 'price' => 1000, 'is_active' => true]);
        PublicHotelContext::clear();

        return $rt;
    }

    public function test_export_only_contains_this_hotels_data(): void
    {
        $this->makeRoomType($this->hotelA, 'Hotel A Room');
        $this->makeRoomType($this->hotelB, 'Hotel B Room');

        $path = Storage::disk('local')->path('test-export-a.json');
        app(HotelDataBackupService::class)->dump($this->hotelA->id, $path);

        $data = json_decode(file_get_contents($path), true);
        $this->assertSame($this->hotelA->id, $data['hotel_id']);
        $names = array_column($data['tables']['room_types'], 'name');
        $this->assertContains('Hotel A Room', $names);
        $this->assertNotContains('Hotel B Room', $names);
    }

    public function test_restore_round_trip_does_not_touch_other_hotel(): void
    {
        $rtA = $this->makeRoomType($this->hotelA, 'Original A Room');
        $this->makeRoomType($this->hotelB, 'Hotel B Room');

        $service = app(HotelDataBackupService::class);
        $path = Storage::disk('local')->path('test-backup-a.json');
        $service->dump($this->hotelA->id, $path);

        // Mutate hotel A's data, then restore from the earlier snapshot.
        PublicHotelContext::set($this->hotelA);
        RoomType::where('id', $rtA->id)->delete();
        PublicHotelContext::clear();

        $this->assertSame(0, RoomType::withoutGlobalScopes()->where('id', $rtA->id)->count());

        // Backdate the snapshot check by forcing (restore would otherwise be a
        // no-op comparison of "now" against "now" from the same test run).
        $service->restore($this->hotelA->id, $path, true);

        $this->assertSame(1, RoomType::withoutGlobalScopes()->where('id', $rtA->id)->count());
        $this->assertSame(1, RoomType::withoutGlobalScopes()->where('hotel_id', $this->hotelB->id)->count());
    }

    public function test_restore_is_blocked_when_hotel_has_newer_data_and_succeeds_with_force(): void
    {
        $service = app(HotelDataBackupService::class);
        $this->makeRoomType($this->hotelA, 'Snapshot Room');

        $path = Storage::disk('local')->path('test-stale.json');
        $service->dump($this->hotelA->id, $path);

        // New data created after the snapshot.
        sleep(1);
        $this->makeRoomType($this->hotelA, 'Newer Room');

        $this->expectException(\App\Exceptions\HotelBackupStaleDataException::class);
        $service->restore($this->hotelA->id, $path, false);
    }

    public function test_restore_with_force_overrides_staleness_block(): void
    {
        $service = app(HotelDataBackupService::class);
        $this->makeRoomType($this->hotelA, 'Snapshot Room');

        $path = Storage::disk('local')->path('test-stale-force.json');
        $service->dump($this->hotelA->id, $path);

        sleep(1);
        $this->makeRoomType($this->hotelA, 'Newer Room');

        $service->restore($this->hotelA->id, $path, true);

        // Restore replaced hotel A's room_types with the snapshot (1 room, not 2).
        $this->assertSame(1, RoomType::withoutGlobalScopes()->where('hotel_id', $this->hotelA->id)->count());
    }

    public function test_restore_rejects_a_backup_from_a_different_hotel(): void
    {
        $service = app(HotelDataBackupService::class);
        $this->makeRoomType($this->hotelA, 'Hotel A Room');

        $path = Storage::disk('local')->path('test-cross-hotel.json');
        $service->dump($this->hotelA->id, $path);

        $this->expectExceptionMessage('belongs to a different hotel');
        $service->restore($this->hotelB->id, $path, true);
    }

    public function test_restore_rejects_the_whole_database_sql_format(): void
    {
        $path = Storage::disk('local')->path('test-full-db.sql');
        file_put_contents($path, "-- Database backup of `hotel_beach_way`\nSET NAMES utf8mb4;\n");

        $this->expectExceptionMessage('not a hotel backup created by this application');
        app(HotelDataBackupService::class)->restore($this->hotelA->id, $path, true);
    }

    public function test_hotel_admin_can_only_see_their_own_hotels_backups(): void
    {
        Backup::create(['hotel_id' => $this->hotelA->id, 'filename' => 'a.json', 'path' => 'backups/hotel-' . $this->hotelA->id . '/a.json', 'size' => 10, 'status' => 'completed', 'created_by' => $this->adminA->id]);
        Backup::create(['hotel_id' => $this->hotelB->id, 'filename' => 'b.json', 'path' => 'backups/hotel-' . $this->hotelB->id . '/b.json', 'size' => 10, 'status' => 'completed', 'created_by' => $this->adminB->id]);

        $this->actingAs($this->adminA)
            ->get(route('admin.hotel-backups.index'))
            ->assertInertia(fn ($page) => $page
                ->has('backups', 1)
                ->where('backups.0.filename', 'a.json')
            );
    }

    public function test_hotel_admin_cannot_download_another_hotels_backup(): void
    {
        $backupB = Backup::create(['hotel_id' => $this->hotelB->id, 'filename' => 'b.json', 'path' => 'backups/hotel-' . $this->hotelB->id . '/b.json', 'size' => 10, 'status' => 'completed', 'created_by' => $this->adminB->id]);

        $this->actingAs($this->adminA)
            ->get(route('admin.hotel-backups.download', $backupB->id))
            ->assertNotFound();
    }

    public function test_full_store_and_restore_flow_via_http(): void
    {
        $this->makeRoomType($this->hotelA, 'Http Flow Room');

        $this->actingAs($this->adminA)
            ->post(route('admin.hotel-backups.store'))
            ->assertRedirect();

        $backup = Backup::where('hotel_id', $this->hotelA->id)->latest()->first();
        $this->assertNotNull($backup);
        $this->assertSame('completed', $backup->status);

        $this->actingAs($this->adminA)
            ->post(route('admin.hotel-backups.restore', $backup->id), ['force' => 1])
            ->assertRedirect();

        $this->assertSame(1, RoomType::withoutGlobalScopes()->where('hotel_id', $this->hotelA->id)->count());
    }
}
