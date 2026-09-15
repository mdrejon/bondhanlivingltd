<?php

use App\Http\Controllers\FrontendController;
use App\Http\Controllers\FrontendBookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RoomTypeController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\RoomBookingController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\PackageBookingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\WebsiteSettings\SliderController;
use App\Http\Controllers\Admin\WebsiteSettings\HeaderSettingController;
use App\Http\Controllers\Admin\WebsiteSettings\FooterSettingController;
use App\Http\Controllers\Admin\WebsiteSettings\AboutSettingController;
use App\Http\Controllers\Admin\WebsiteSettings\GalleryController;
use App\Http\Controllers\Admin\WebsiteSettings\ContactSettingController;
use App\Http\Controllers\Admin\WebsiteSettings\ServiceSettingController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\RoomAvailabilityController;
use App\Http\Controllers\Admin\WebsiteSettings\HistorySettingController;
use App\Http\Controllers\Admin\WebsiteSettings\FaqPageSettingController;
use App\Http\Controllers\Admin\WebsiteSettings\BlogSettingController;
use App\Http\Controllers\Admin\WebsiteSettings\RoomsSettingController;
use App\Http\Controllers\Admin\WebsiteSettings\BookingSettingController;
use App\Http\Controllers\Admin\WebsiteSettings\EmailSettingController;
use App\Http\Controllers\Admin\WebsiteSettings\MailSettingController;

use App\Http\Controllers\Admin\BookingFollowUpController;
use App\Http\Controllers\Admin\RoomAmenityController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\HotelBackupController;
use App\Http\Controllers\Admin\HotelController;
use App\Http\Controllers\Admin\ActingHotelController;
use App\Http\Controllers\Admin\GovernmentReportController;
use App\Http\Controllers\Admin\DocumentController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Frontend / Public Routes
Route::get('/',           [FrontendController::class, 'home'])->name('home');
Route::get('/rooms',      [FrontendController::class, 'rooms'])->name('rooms');
Route::get('/rooms/{slug}', [FrontendController::class, 'roomDetail'])->name('rooms.detail');
Route::get('/about',      [FrontendController::class, 'about'])->name('about');
Route::get('/history',    [FrontendController::class, 'history'])->name('history');
Route::get('/services',          [FrontendController::class, 'services'])->name('services.front');
Route::get('/services/{slug}',   [FrontendController::class, 'serviceDetail'])->name('service.detail');
Route::get('/gallery',    [FrontendController::class, 'gallery'])->name('gallery.front');
Route::get('/faqs',         [FrontendController::class, 'faqs'])->name('faqs.front');
Route::get('/facilities',   [FrontendController::class, 'facilities'])->name('facilities.front');
Route::get('/blog',           [FrontendController::class, 'blog'])->name('blog.front');
Route::get('/blog/{slug}',    [FrontendController::class, 'blogDetail'])->name('blog.detail');
Route::post('/blog/comment',  [FrontendController::class, 'submitBlogComment'])->name('blog.comment')->middleware('throttle:6,1');
Route::get('/contact',    [FrontendController::class, 'contact'])->name('contact');
Route::post('/contact',   [FrontendController::class, 'submitContact'])->name('contact.submit')->middleware('throttle:6,1');
Route::get('/booking',    [FrontendController::class, 'booking'])->name('booking');
Route::post('/booking/check-availability', [FrontendBookingController::class, 'checkAvailability'])->name('booking.check-availability')->middleware('throttle:20,1');
Route::post('/booking',   [FrontendBookingController::class, 'store'])->name('booking.store')->middleware('throttle:6,1');
Route::post('/inquiry',   [FrontendController::class, 'submitInquiry'])->name('inquiry.store')->middleware('throttle:6,1');

// Per-hotel public websites (Phase 8A) — same FrontendController/FrontendBookingController
// actions as the root-level routes above; ResolvePublicHotel resolves {hotelSlug} and every
// tenant-scoped query (RoomType, Service, GlobalSetting, ...) picks it up automatically via
// CurrentHotel. Route::name('hotel.') prefixes each ->name(...) below, so 'home' becomes
// 'hotel.home', etc. — no name collisions with the root-level routes, no per-route renaming.
Route::prefix('hotel/{hotelSlug}')->name('hotel.')->middleware('resolve.public-hotel')->group(function () {
    Route::get('/',           [FrontendController::class, 'home'])->name('home');
    Route::get('/rooms',      [FrontendController::class, 'rooms'])->name('rooms');
    Route::get('/rooms/{slug}', [FrontendController::class, 'roomDetail'])->name('rooms.detail');
    Route::get('/about',      [FrontendController::class, 'about'])->name('about');
    Route::get('/history',    [FrontendController::class, 'history'])->name('history');
    Route::get('/services',          [FrontendController::class, 'services'])->name('services.front');
    Route::get('/services/{slug}',   [FrontendController::class, 'serviceDetail'])->name('service.detail');
    Route::get('/gallery',    [FrontendController::class, 'gallery'])->name('gallery.front');
    Route::get('/faqs',         [FrontendController::class, 'faqs'])->name('faqs.front');
    Route::get('/facilities',   [FrontendController::class, 'facilities'])->name('facilities.front');
    Route::get('/blog',           [FrontendController::class, 'blog'])->name('blog.front');
    Route::get('/blog/{slug}',    [FrontendController::class, 'blogDetail'])->name('blog.detail');
    Route::post('/blog/comment',  [FrontendController::class, 'submitBlogComment'])->name('blog.comment')->middleware('throttle:6,1');
    Route::get('/contact',    [FrontendController::class, 'contact'])->name('contact');
    Route::post('/contact',   [FrontendController::class, 'submitContact'])->name('contact.submit')->middleware('throttle:6,1');
    Route::get('/booking',    [FrontendController::class, 'booking'])->name('booking');
    Route::post('/booking/check-availability', [FrontendBookingController::class, 'checkAvailability'])->name('booking.check-availability')->middleware('throttle:20,1');
    Route::post('/booking',   [FrontendBookingController::class, 'store'])->name('booking.store')->middleware('throttle:6,1');
    Route::post('/inquiry',   [FrontendController::class, 'submitInquiry'])->name('inquiry.store')->middleware('throttle:6,1');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'module.permission'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Protected document viewing (hotel legal docs + guest KYC documents) — auth +
    // scope/permission re-checked per request in DocumentController itself, since a
    // single generic module key can't express "hotel-registration OR customers"
    // depending on which document this is. See App\Support\DocumentUploader's docblock.
    Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');

    // Room Types CRUD
    Route::get('/room-types',                   [RoomTypeController::class, 'index'])->name('room-types.index');
    Route::get('/room-types/create',            [RoomTypeController::class, 'create'])->name('room-types.create');
    Route::post('/room-types',                  [RoomTypeController::class, 'store'])->name('room-types.store');
    Route::get('/room-types/{roomType}/edit',   [RoomTypeController::class, 'edit'])->name('room-types.edit');
    Route::post('/room-types/{roomType}',       [RoomTypeController::class, 'update'])->name('room-types.update');
    Route::delete('/room-types/{roomType}',                      [RoomTypeController::class, 'destroy'])->name('room-types.destroy');
    Route::patch('/room-types/{roomType}/toggle',                [RoomTypeController::class, 'toggleStatus'])->name('room-types.toggle');
    Route::delete('/room-types/{roomType}/gallery-image',        [RoomTypeController::class, 'deleteGalleryImage'])->name('room-types.gallery-image.delete');

    // Rooms CRUD
    Route::get('/rooms',                    [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/rooms/create',             [RoomController::class, 'create'])->name('rooms.create');
    Route::post('/rooms',                   [RoomController::class, 'store'])->name('rooms.store');
    Route::get('/rooms/{room}/edit',        [RoomController::class, 'edit'])->name('rooms.edit');
    Route::post('/rooms/{room}',            [RoomController::class, 'update'])->name('rooms.update');
    Route::delete('/rooms/{room}',          [RoomController::class, 'destroy'])->name('rooms.destroy');
    Route::patch('/rooms/{room}/toggle',    [RoomController::class, 'toggleStatus'])->name('rooms.toggle');

    // Room Availability Checker
    Route::get('/room-availability', [RoomAvailabilityController::class, 'index'])->name('room-availability.index');

    // Room Bookings
    Route::get('/room-bookings',                            [RoomBookingController::class, 'index'])->name('room-bookings.index');
    Route::get('/room-bookings/create',                     [RoomBookingController::class, 'create'])->name('room-bookings.create');
    Route::get('/room-bookings/room-availability',          [RoomBookingController::class, 'roomAvailability'])->name('room-bookings.room-availability');
    Route::post('/room-bookings',                           [RoomBookingController::class, 'store'])->name('room-bookings.store');
    Route::get('/room-bookings/{roomBooking}',              [RoomBookingController::class, 'show'])->name('room-bookings.show');
    Route::get('/room-bookings/{roomBooking}/invoice',      [RoomBookingController::class, 'invoicePdf'])->name('room-bookings.invoice');
    Route::get('/room-bookings/{roomBooking}/receipt',      [RoomBookingController::class, 'moneyReceiptPdf'])->name('room-bookings.receipt');
    Route::get('/room-bookings/{roomBooking}/edit',         [RoomBookingController::class, 'edit'])->name('room-bookings.edit');
    Route::post('/room-bookings/{roomBooking}',             [RoomBookingController::class, 'update'])->name('room-bookings.update');
    Route::patch('/room-bookings/{roomBooking}/status',     [RoomBookingController::class, 'updateStatus'])->name('room-bookings.update-status');
    Route::post('/room-bookings/{roomBooking}/checkout',   [RoomBookingController::class, 'checkoutWithPayment'])->name('room-bookings.checkout-payment');
    Route::delete('/room-bookings/{roomBooking}',           [RoomBookingController::class, 'destroy'])->name('room-bookings.destroy');

    // Extended Booking Views
    Route::get('/bookings/all',       [RoomBookingController::class, 'allBookings'])->name('bookings.all');
    Route::get('/bookings/online',    [RoomBookingController::class, 'onlineBookings'])->name('bookings.online');
    Route::get('/bookings/manual',    [RoomBookingController::class, 'manualBookings'])->name('bookings.manual');
    Route::get('/bookings/cancelled', [RoomBookingController::class, 'cancelledBookings'])->name('bookings.cancelled');
    Route::get('/bookings/history',   [RoomBookingController::class, 'history'])->name('bookings.history');
    Route::get('/bookings/export/pdf',   [RoomBookingController::class, 'exportPdf'])->name('bookings.export.pdf');
    Route::get('/bookings/export/print', [RoomBookingController::class, 'printView'])->name('bookings.export.print');

    // Booking Follow-up
    Route::get('/bookings/follow-up',               [BookingFollowUpController::class, 'index'])->name('bookings.follow-up');
    Route::post('/bookings/{booking}/follow-up',    [BookingFollowUpController::class, 'store'])->name('bookings.follow-up.store');
    Route::delete('/bookings/follow-up/{followUp}', [BookingFollowUpController::class, 'destroy'])->name('bookings.follow-up.destroy');

    // Inquiries
    Route::get('/inquiries',                         [InquiryController::class, 'index'])->name('inquiries.index');
    Route::get('/inquiries/{inquiry}',               [InquiryController::class, 'show'])->name('inquiries.show');
    Route::patch('/inquiries/{inquiry}/status',      [InquiryController::class, 'updateStatus'])->name('inquiries.update-status');
    Route::delete('/inquiries/{inquiry}',            [InquiryController::class, 'destroy'])->name('inquiries.destroy');

    // Customers
    Route::get('/customers',             [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/lookup',      [CustomerController::class, 'lookup'])->name('customers.lookup');
    Route::get('/customers/suggest',     [CustomerController::class, 'suggest'])->name('customers.suggest');
    Route::get('/customers/history',     [CustomerController::class, 'history'])->name('customers.history');
    Route::get('/customers/{customer}',              [CustomerController::class, 'show'])->name('customers.show');
    Route::get('/customers/{customer}/edit',         [CustomerController::class, 'edit'])->name('customers.edit');
    Route::post('/customers/{customer}',             [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/documents/{document}', [CustomerController::class, 'deleteDocument'])->name('customers.documents.delete');

    // Packages Management
    Route::get('/packages',           [PackageController::class, 'index'])->name('packages.index');
    Route::get('/packages/create',    [PackageController::class, 'create'])->name('packages.create');
    Route::get('/packages/{id}/edit', [PackageController::class, 'edit'])->name('packages.edit');

    // Package Bookings
    Route::get('/package-bookings',      [PackageBookingController::class, 'index'])->name('package-bookings.index');
    Route::get('/package-bookings/{id}', [PackageBookingController::class, 'show'])->name('package-bookings.show');

    // Users Management (full CRUD)
    Route::get('/users',                [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create',         [UserController::class, 'create'])->name('users.create');
    Route::post('/users',               [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit',    [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}',         [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}',      [UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{user}/toggle',[UserController::class, 'toggleStatus'])->name('users.toggle');

    // Hotel Registration (Super Admin — multi-tenant hotel onboarding)
    Route::get('/hotels',                       [HotelController::class, 'index'])->name('hotels.index');
    Route::get('/hotels/create',                [HotelController::class, 'create'])->name('hotels.create');
    Route::post('/hotels',                      [HotelController::class, 'store'])->name('hotels.store');
    Route::get('/hotels/{hotel}',               [HotelController::class, 'show'])->name('hotels.show');
    Route::get('/hotels/{hotel}/edit',          [HotelController::class, 'edit'])->name('hotels.edit');
    Route::post('/hotels/{hotel}',              [HotelController::class, 'update'])->name('hotels.update');
    Route::patch('/hotels/{hotel}/status',      [HotelController::class, 'updateStatus'])->name('hotels.update-status');
    Route::delete('/hotels/documents/{document}', [HotelController::class, 'deleteDocument'])->name('hotels.documents.delete');

    // Super Admin "acting as" hotel switcher (Phase 8A)
    Route::post('/acting-hotel', [ActingHotelController::class, 'update'])->name('acting-hotel.update');

    // Government Monitoring Reports (DC Office / UNO Office / Police Admin)
    Route::get('/government/hotels',       [GovernmentReportController::class, 'hotels'])->name('government.hotels');
    Route::get('/government/guests',       [GovernmentReportController::class, 'guests'])->name('government.guests');
    Route::get('/government/nationality',  [GovernmentReportController::class, 'nationality'])->name('government.nationality');
    Route::get('/government/nid-search',   [GovernmentReportController::class, 'nidSearch'])->name('government.nid-search');
    Route::get('/government/export/csv',   [GovernmentReportController::class, 'exportCsv'])->name('government.export.csv');
    Route::get('/government/export/pdf',   [GovernmentReportController::class, 'exportPdf'])->name('government.export.pdf');

    // Roles Management
    Route::get('/roles',                [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create',         [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles',               [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}/edit',    [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}',         [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}',      [RoleController::class, 'destroy'])->name('roles.destroy');

    // FAQ Management
    Route::get('/faqs',                [FaqController::class, 'index'])->name('faqs.index');
    Route::get('/faqs/create',         [FaqController::class, 'create'])->name('faqs.create');
    Route::post('/faqs',               [FaqController::class, 'store'])->name('faqs.store');
    Route::get('/faqs/{faq}/edit',     [FaqController::class, 'edit'])->name('faqs.edit');
    Route::put('/faqs/{faq}',          [FaqController::class, 'update'])->name('faqs.update');
    Route::delete('/faqs/{faq}',       [FaqController::class, 'destroy'])->name('faqs.destroy');
    Route::patch('/faqs/{faq}/toggle', [FaqController::class, 'toggleStatus'])->name('faqs.toggle');

    // Services CRUD
    Route::get('/services',                [ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/create',         [ServiceController::class, 'create'])->name('services.create');
    Route::post('/services',               [ServiceController::class, 'store'])->name('services.store');
    Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    Route::put('/services/{service}',      [ServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{service}',   [ServiceController::class, 'destroy'])->name('services.destroy');
    Route::patch('/services/{service}/toggle', [ServiceController::class, 'toggleStatus'])->name('services.toggle');

    // Facilities CRUD
    Route::get('/facilities',                         [FacilityController::class, 'index'])->name('facilities.index');
    Route::post('/facilities',                        [FacilityController::class, 'store'])->name('facilities.store');
    Route::put('/facilities/{facility}',              [FacilityController::class, 'update'])->name('facilities.update');
    Route::delete('/facilities/{facility}',           [FacilityController::class, 'destroy'])->name('facilities.destroy');
    Route::patch('/facilities/{facility}/toggle',     [FacilityController::class, 'toggleStatus'])->name('facilities.toggle');
    Route::post('/facilities/settings',               [FacilityController::class, 'updateSettings'])->name('facilities.settings');

    // Blog Posts CRUD
    Route::get('/blog',                        [BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/create',                 [BlogController::class, 'create'])->name('blog.create');
    Route::post('/blog',                       [BlogController::class, 'store'])->name('blog.store');
    Route::get('/blog/{blog}/edit',            [BlogController::class, 'edit'])->name('blog.edit');
    Route::post('/blog/{blog}',                [BlogController::class, 'update'])->name('blog.update');
    Route::delete('/blog/{blog}',              [BlogController::class, 'destroy'])->name('blog.destroy');
    Route::patch('/blog/{blog}/toggle',        [BlogController::class, 'toggleStatus'])->name('blog.toggle');
    Route::get('/blog/{blog}/comments',        [BlogController::class, 'comments'])->name('blog.comments');
    Route::patch('/blog-comments/{comment}/approve', [BlogController::class, 'approveComment'])->name('blog-comments.approve');
    Route::delete('/blog-comments/{comment}',  [BlogController::class, 'deleteComment'])->name('blog-comments.destroy');

    // Blog Comments
    Route::get('/blog-comments',                              [BlogController::class, 'allComments'])->name('blog-comments.index');

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/income',     [ReportController::class, 'income'])->name('income');
        Route::get('/discount',   [ReportController::class, 'discount'])->name('discount');
        Route::get('/booking',    [ReportController::class, 'booking'])->name('booking');
        Route::get('/export/csv', [ReportController::class, 'exportCsv'])->name('export.csv');
        Route::get('/export/pdf', [ReportController::class, 'exportPdf'])->name('export.pdf');
    });

    // Blog Categories CRUD
    Route::get('/blog-categories',                        [BlogCategoryController::class, 'index'])->name('blog-categories.index');
    Route::post('/blog-categories',                       [BlogCategoryController::class, 'store'])->name('blog-categories.store');
    Route::put('/blog-categories/{blogCategory}',         [BlogCategoryController::class, 'update'])->name('blog-categories.update');
    Route::delete('/blog-categories/{blogCategory}',      [BlogCategoryController::class, 'destroy'])->name('blog-categories.destroy');
    Route::patch('/blog-categories/{blogCategory}/toggle',[BlogCategoryController::class, 'toggleStatus'])->name('blog-categories.toggle');

    // Testimonials CRUD
    Route::get('/testimonials',                       [TestimonialController::class, 'index'])->name('testimonials.index');
    Route::post('/testimonials',                      [TestimonialController::class, 'store'])->name('testimonials.store');
    Route::put('/testimonials/{testimonial}',         [TestimonialController::class, 'update'])->name('testimonials.update');
    Route::delete('/testimonials/{testimonial}',      [TestimonialController::class, 'destroy'])->name('testimonials.destroy');
    Route::patch('/testimonials/{testimonial}/toggle',[TestimonialController::class, 'toggleStatus'])->name('testimonials.toggle');
    Route::post('/testimonials/settings',             [TestimonialController::class, 'updateSettings'])->name('testimonials.settings');

    // Room Amenities CRUD
    Route::prefix('room-amenities')->name('room-amenities.')->group(function () {
        Route::get('/',                  [RoomAmenityController::class, 'index'])->name('index');
        Route::post('/',                 [RoomAmenityController::class, 'store'])->name('store');
        Route::put('/{roomAmenity}',     [RoomAmenityController::class, 'update'])->name('update');
        Route::delete('/{roomAmenity}',  [RoomAmenityController::class, 'destroy'])->name('destroy');
    });

    // Website Settings
    Route::prefix('website-settings')->name('website-settings.')->group(function () {
        // Hero Slider CRUD
        Route::get('/sliders',                  [SliderController::class, 'index'])->name('sliders.index');
        Route::get('/sliders/create',           [SliderController::class, 'create'])->name('sliders.create');
        Route::post('/sliders',                 [SliderController::class, 'store'])->name('sliders.store');
        Route::get('/sliders/{slider}/edit',    [SliderController::class, 'edit'])->name('sliders.edit');
        Route::post('/sliders/{slider}',        [SliderController::class, 'update'])->name('sliders.update');
        Route::delete('/sliders/{slider}',      [SliderController::class, 'destroy'])->name('sliders.destroy');
        Route::patch('/sliders/{slider}/toggle',[SliderController::class, 'toggleStatus'])->name('sliders.toggle');

        // Header Settings
        Route::get('/header',  [HeaderSettingController::class, 'edit'])->name('header.edit');
        Route::post('/header', [HeaderSettingController::class, 'update'])->name('header.update');

        // Footer Settings
        Route::get('/footer',  [FooterSettingController::class, 'edit'])->name('footer.edit');
        Route::post('/footer', [FooterSettingController::class, 'update'])->name('footer.update');

        // About Section Settings
        Route::get('/about',  [AboutSettingController::class, 'edit'])->name('about.edit');
        Route::post('/about', [AboutSettingController::class, 'update'])->name('about.update');

        // History Page Settings
        Route::get('/history',  [HistorySettingController::class, 'edit'])->name('history.edit');
        Route::put('/history',  [HistorySettingController::class, 'update'])->name('history.update');

        // FAQ Page Settings
        Route::get('/faq-page',  [FaqPageSettingController::class, 'edit'])->name('faq-page.edit');
        Route::put('/faq-page',  [FaqPageSettingController::class, 'update'])->name('faq-page.update');

        // Blog Page Settings
        Route::get('/blog-page',  [BlogSettingController::class, 'edit'])->name('blog-page.edit');
        Route::put('/blog-page',  [BlogSettingController::class, 'update'])->name('blog-page.update');

        // Rooms Page Settings
        Route::get('/rooms-page',  [RoomsSettingController::class, 'edit'])->name('rooms-page.edit');
        Route::put('/rooms-page',  [RoomsSettingController::class, 'update'])->name('rooms-page.update');

        // Booking Page Settings
        Route::get('/booking-page',  [BookingSettingController::class, 'edit'])->name('booking-page.edit');
        Route::put('/booking-page',  [BookingSettingController::class, 'update'])->name('booking-page.update');

        // Contact Settings
        Route::get('/contact',  [ContactSettingController::class, 'edit'])->name('contact.edit');
        Route::post('/contact', [ContactSettingController::class, 'update'])->name('contact.update');

        // Services Section Settings
        Route::get('/services',  [ServiceSettingController::class, 'edit'])->name('services.edit');
        Route::post('/services', [ServiceSettingController::class, 'update'])->name('services.update');

        // Mail / SMTP Settings
        Route::get('/mail',       [MailSettingController::class, 'edit'])->name('mail.edit');
        Route::post('/mail',      [MailSettingController::class, 'update'])->name('mail.update');
        Route::post('/mail/test', [MailSettingController::class, 'sendTest'])->name('mail.test');

        // Email Notification Settings (per-status toggles + editable template copy)
        Route::get('/email-notifications',  [EmailSettingController::class, 'edit'])->name('email-notifications.edit');
        Route::put('/email-notifications',  [EmailSettingController::class, 'update'])->name('email-notifications.update');

        // Gallery CRUD
        Route::get('/gallery',                           [GalleryController::class, 'index'])->name('gallery.index');
        Route::post('/gallery',                          [GalleryController::class, 'store'])->name('gallery.store');
        Route::patch('/gallery/{gallery}',               [GalleryController::class, 'update'])->name('gallery.update');
        Route::delete('/gallery/{gallery}',              [GalleryController::class, 'destroy'])->name('gallery.destroy');
        Route::patch('/gallery/{gallery}/toggle',        [GalleryController::class, 'toggleStatus'])->name('gallery.toggle');
        Route::post('/gallery/reorder',                  [GalleryController::class, 'reorder'])->name('gallery.reorder');
        Route::post('/gallery/settings',                 [GalleryController::class, 'updateSettings'])->name('gallery.settings');
    });

    // Database Backup
    Route::prefix('backups')->name('backups.')->group(function () {
        Route::get('/',                    [BackupController::class, 'index'])->name('index');
        Route::post('/',                   [BackupController::class, 'store'])->name('store');
        Route::post('/restore-upload',     [BackupController::class, 'restoreUpload'])->name('restore-upload');
        Route::get('/{backup}/download',   [BackupController::class, 'download'])->name('download');
        Route::post('/{backup}/restore',   [BackupController::class, 'restore'])->name('restore');
        Route::delete('/{backup}',         [BackupController::class, 'destroy'])->name('destroy');
    });

    // Per-hotel Backup (Phase 8B — hotel-scoped, distinct from the platform backups above)
    Route::prefix('hotel-backups')->name('hotel-backups.')->group(function () {
        Route::get('/',                    [HotelBackupController::class, 'index'])->name('index');
        Route::post('/',                   [HotelBackupController::class, 'store'])->name('store');
        Route::post('/restore-upload',     [HotelBackupController::class, 'restoreUpload'])->name('restore-upload');
        Route::get('/{backup}/download',   [HotelBackupController::class, 'download'])->name('download');
        Route::post('/{backup}/restore',   [HotelBackupController::class, 'restore'])->name('restore');
        Route::delete('/{backup}',         [HotelBackupController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__.'/auth.php';
