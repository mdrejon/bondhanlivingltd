<?php

namespace Tests\Feature;

use App\Mail\BookingConfirmationMail;
use App\Mail\BookingNotificationMail;
use App\Models\Customer;
use App\Models\Hotel;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\Room;
use App\Models\RoomBooking;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Phase 7 full regression pass: the booking flow is Hotel Beach Way's original,
 * core feature and had no automated coverage at all before this — six phases of
 * multi-tenancy/RBAC/KYC changes touched the models it depends on
 * (Customer/RoomBooking/BookingRoom all gained hotel_id + global scopes) without a
 * single test confirming booking creation still actually works end to end. Fixed here
 * rather than left as a gap. Per docs/hgrm-saas/live_smtp_warning: this environment's
 * mail config routes to a real SMTP server, so every test below uses Mail::fake() —
 * never call these controllers without it.
 */
class BookingFlowRegressionTest extends TestCase
{
    use RefreshDatabase;

    private Hotel $hotel;
    private RoomType $roomType;
    private Room $room;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();

        $this->hotel = Hotel::withoutGlobalScopes()->where('slug', 'hotel-beach-way')->firstOrFail();
        $this->roomType = RoomType::create([
            'hotel_id' => $this->hotel->id, 'name' => 'Deluxe', 'slug' => 'deluxe-' . uniqid(),
            'price' => 3000, 'max_adults' => 2, 'max_children' => 1, 'is_active' => true,
        ]);
        $this->room = Room::create(['hotel_id' => $this->hotel->id, 'room_type_id' => $this->roomType->id, 'room_number' => 'R-' . uniqid(), 'is_active' => true]);
    }

    public function test_public_booking_availability_check_works(): void
    {
        $this->postJson(route('booking.check-availability'), [
            'checkin'  => now()->addDay()->toDateString(),
            'checkout' => now()->addDays(2)->toDateString(),
        ])->assertOk()->assertJsonPath('available', true);
    }

    public function test_public_booking_creates_customer_and_booking_correctly_scoped(): void
    {
        $response = $this->postJson(route('booking.store'), [
            'name' => 'Regression Guest', 'email' => 'guest@example.test', 'phone' => '01799990000',
            'checkin' => now()->addDay()->toDateString(), 'checkout' => now()->addDays(2)->toDateString(),
            'rooms' => [['room_slug' => $this->roomType->slug, 'quantity' => 1, 'adults' => 2, 'children' => 0]],
            // SpamGuard requires this signed "the form has been open a few seconds"
            // token — without it every public submission is silently no-op'd as spam.
            'form_rendered_at' => encrypt(time() - 10),
        ]);

        $response->assertOk()->assertJsonPath('success', true);

        $customer = Customer::where('phone', '01799990000')->first();
        $this->assertNotNull($customer);
        $this->assertSame($this->hotel->id, $customer->hotel_id);

        $booking = RoomBooking::where('customer_id', $customer->id)->first();
        $this->assertNotNull($booking);
        $this->assertSame($this->hotel->id, $booking->hotel_id);
        $this->assertSame(1, $booking->rooms()->count());

        Mail::assertSent(BookingNotificationMail::class);
    }

    public function test_admin_can_create_a_manual_booking_for_their_hotel(): void
    {
        $role = Role::create(['name' => 'Front Desk', 'slug' => 'fd-' . uniqid(), 'is_super_admin' => false, 'is_active' => true, 'scope_type' => 'hotel']);
        RolePermission::create(['role_id' => $role->id, 'module_key' => 'bookings', 'can_view' => true, 'can_create' => true]);
        $staff = User::create(['name' => 'Staff', 'email' => uniqid() . '@t.local', 'password' => 'password', 'role_id' => $role->id, 'hotel_id' => $this->hotel->id, 'is_active' => true]);

        $response = $this->actingAs($staff)->post(route('admin.room-bookings.store'), [
            'check_in_date' => now()->addDay()->toDateString(),
            'check_out_date' => now()->addDays(2)->toDateString(),
            'payment_method' => 'cash',
            'booking_status' => 'confirmed',
            'customer_name' => 'Manual Guest', 'customer_phone' => '01788880000',
            'rooms' => [['room_type_id' => $this->roomType->id, 'room_id' => $this->room->id, 'adults' => 1, 'children' => 0, 'price_per_night' => 3000]],
        ]);

        $response->assertRedirect();
        $booking = RoomBooking::whereHas('customer', fn ($q) => $q->where('phone', '01788880000'))->first();
        $this->assertNotNull($booking);
        $this->assertSame($this->hotel->id, $booking->hotel_id);
        $this->assertSame('confirmed', $booking->booking_status);
    }
}
