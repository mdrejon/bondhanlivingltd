<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\District;
use App\Models\GlobalSetting;
use App\Models\Hotel;
use App\Models\PoliceStation;
use App\Models\Room;
use App\Models\RoomBooking;
use App\Models\RoomType;
use App\Models\Service;
use App\Support\PublicHotelContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Phase 8A — each hotel gets its own public site at /hotel/{slug}, with its own
 * scoped content (About/Services/etc.) and SMTP config, resolved via
 * App\Support\PublicHotelContext + ResolvePublicHotel middleware. The root-level
 * site (/) must keep serving the primary hotel exactly as before — zero URL
 * breakage for the live hotel. Per docs/hgrm-saas/live_smtp_warning: this
 * environment's mail config can route to a real SMTP server, so every test that
 * triggers an email uses Mail::fake().
 */
class MultiTenantFrontendTest extends TestCase
{
    use RefreshDatabase;

    private Hotel $primary;
    private Hotel $second;

    protected function setUp(): void
    {
        parent::setUp();

        $this->primary = Hotel::withoutGlobalScopes()->where('slug', 'hotel-beach-way')->firstOrFail();

        $district = District::first();
        $this->second = Hotel::withoutGlobalScopes()->create([
            'name' => 'Second Test Hotel', 'slug' => 'second-test-hotel',
            'mobile' => '01711111111', 'address' => 'Somewhere else',
            'district_id' => $district->id,
            'police_station_id' => PoliceStation::where('district_id', $district->id)->firstOrFail()->id,
            'status' => 'active',
        ]);
    }

    public function test_second_hotels_site_only_shows_its_own_services(): void
    {
        Service::create(['hotel_id' => $this->primary->id, 'title' => 'Primary Only Service', 'slug' => 'primary-only', 'short_desc' => 'x', 'description' => 'x', 'is_active' => true]);
        Service::create(['hotel_id' => $this->second->id, 'title' => 'Second Only Service', 'slug' => 'second-only', 'short_desc' => 'x', 'description' => 'x', 'is_active' => true]);

        $this->get(route('hotel.services.front', ['hotelSlug' => $this->second->slug]))
            ->assertOk()
            ->assertSee('Second Only Service')
            ->assertDontSee('Primary Only Service');

        // Root-level routes never run ResolvePublicHotel — reset the static context
        // the previous request's middleware set, exactly as a fresh production
        // process would already have it (see PublicHotelContext::clear()).
        PublicHotelContext::clear();

        $this->get(route('services.front'))
            ->assertOk()
            ->assertSee('Primary Only Service')
            ->assertDontSee('Second Only Service');
    }

    public function test_unknown_hotel_slug_404s(): void
    {
        $this->get('/hotel/does-not-exist')->assertNotFound();
    }

    public function test_root_site_is_unaffected_by_a_second_hotel_existing(): void
    {
        $this->get(route('home'))->assertOk();
        $this->get(route('about'))->assertOk();
    }

    public function test_booking_on_second_hotels_site_is_scoped_to_that_hotel(): void
    {
        Mail::fake();

        $roomType = RoomType::create(['hotel_id' => $this->second->id, 'name' => 'Deluxe', 'slug' => 'deluxe-' . uniqid(), 'price' => 3000, 'max_adults' => 2, 'max_children' => 1, 'is_active' => true]);
        Room::create(['hotel_id' => $this->second->id, 'room_type_id' => $roomType->id, 'room_number' => 'R-' . uniqid(), 'is_active' => true]);

        $response = $this->postJson(route('hotel.booking.store', ['hotelSlug' => $this->second->slug]), [
            'name' => 'Second Hotel Guest', 'email' => 'guest2@example.test', 'phone' => '01799991111',
            'checkin' => now()->addDay()->toDateString(), 'checkout' => now()->addDays(2)->toDateString(),
            'rooms' => [['room_slug' => $roomType->slug, 'quantity' => 1, 'adults' => 2, 'children' => 0]],
            'form_rendered_at' => encrypt(time() - 10),
        ]);

        $response->assertOk()->assertJsonPath('success', true);

        $customer = Customer::withoutGlobalScopes()->where('phone', '01799991111')->first();
        $this->assertNotNull($customer);
        $this->assertSame($this->second->id, $customer->hotel_id);

        $booking = RoomBooking::withoutGlobalScopes()->where('customer_id', $customer->id)->first();
        $this->assertNotNull($booking);
        $this->assertSame($this->second->id, $booking->hotel_id);
    }

    public function test_global_setting_writes_for_one_hotel_do_not_leak_to_another(): void
    {
        PublicHotelContext::set($this->primary);
        GlobalSetting::set('about_title', 'Primary Hotel About');

        PublicHotelContext::set($this->second);
        GlobalSetting::set('about_title', 'Second Hotel About');

        $this->assertSame('Primary Hotel About', GlobalSetting::withoutGlobalScope('hotel')->where('hotel_id', $this->primary->id)->where('key', 'about_title')->value('value'));
        $this->assertSame('Second Hotel About', GlobalSetting::withoutGlobalScope('hotel')->where('hotel_id', $this->second->id)->where('key', 'about_title')->value('value'));

        $this->get(route('hotel.about', ['hotelSlug' => $this->second->slug]))->assertOk()->assertSee('Second Hotel About');

        PublicHotelContext::clear();
        $this->get(route('about'))->assertDontSee('Second Hotel About');
    }

    public function test_transactional_email_uses_the_bookings_own_hotel_smtp_config(): void
    {
        Mail::fake();

        PublicHotelContext::set($this->second);
        GlobalSetting::setMany([
            'mail_enabled' => '1', 'mail_driver' => 'smtp', 'mail_host' => 'second-hotel-smtp.example.test',
            'mail_from_address' => 'second@example.test', 'mail_from_name' => 'Second Hotel',
        ]);

        $roomType = RoomType::create(['hotel_id' => $this->second->id, 'name' => 'Deluxe', 'slug' => 'deluxe-' . uniqid(), 'price' => 3000, 'max_adults' => 2, 'max_children' => 1, 'is_active' => true]);
        Room::create(['hotel_id' => $this->second->id, 'room_type_id' => $roomType->id, 'room_number' => 'R-' . uniqid(), 'is_active' => true]);

        $this->postJson(route('hotel.booking.store', ['hotelSlug' => $this->second->slug]), [
            'name' => 'SMTP Check Guest', 'email' => 'smtpcheck@example.test', 'phone' => '01799992222',
            'checkin' => now()->addDay()->toDateString(), 'checkout' => now()->addDays(2)->toDateString(),
            'rooms' => [['room_slug' => $roomType->slug, 'quantity' => 1, 'adults' => 2, 'children' => 0]],
            'form_rendered_at' => encrypt(time() - 10),
        ])->assertOk();

        $this->assertSame('second-hotel-smtp.example.test', config('mail.mailers.smtp.host'));
    }
}
