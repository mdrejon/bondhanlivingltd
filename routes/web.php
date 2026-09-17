<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\WebsiteSettings\SliderController;
use App\Http\Controllers\Admin\WebsiteSettings\MailSettingController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\WebsiteSettings\HomePageContentController;
use App\Http\Controllers\Admin\WebsiteSettings\AboutPageContentController;
use App\Http\Controllers\Admin\WebsiteSettings\HistoryPageContentController;
use App\Http\Controllers\Admin\WebsiteSettings\ChairmanMessageContentController;
use App\Http\Controllers\Admin\WebsiteSettings\CorporateBackgroundContentController;
use App\Http\Controllers\Admin\WebsiteSettings\ContactPageContentController;
use Illuminate\Support\Facades\Route;

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

use App\Http\Controllers\FrontendController;

// Frontend Routes
Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/about', [FrontendController::class, 'about'])->name('about');
Route::get('/chairman-message', [FrontendController::class, 'chairmanMessage'])->name('chairman-message');
Route::get('/history', [FrontendController::class, 'history'])->name('history');
Route::get('/corporate-background', [FrontendController::class, 'corporateBackground'])->name('corporate-background');

Route::prefix('projects')->name('projects.')->group(function () {
    Route::get('/', [FrontendController::class, 'projectList'])->name('index');
    Route::get('/running', [FrontendController::class, 'runningProjects'])->name('running');
    Route::get('/completed', [FrontendController::class, 'completedProjects'])->name('completed');
    Route::get('/upcoming', [FrontendController::class, 'upcomingProjects'])->name('upcoming');
    Route::get('/{slug}', [FrontendController::class, 'projectDetails'])->name('show');
});

Route::prefix('services')->name('services.')->group(function () {
    Route::get('/', [FrontendController::class, 'serviceList'])->name('index');
    Route::get('/{slug}', [FrontendController::class, 'serviceDetails'])->name('show');
});

Route::get('/features-amenities', [FrontendController::class, 'featuresAmenities'])->name('features-amenities');
Route::get('/gallery', [FrontendController::class, 'gallery'])->name('gallery');
Route::get('/team', [FrontendController::class, 'team'])->name('team');
Route::get('/blog', [FrontendController::class, 'blog'])->name('blog');
Route::get('/loan-calculator', [FrontendController::class, 'loanCalculator'])->name('loan-calculator');
Route::get('/terms-and-conditions', [FrontendController::class, 'termsAndConditions'])->name('terms-and-conditions');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'module.permission'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Users Management
    Route::get('/users',                [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create',         [UserController::class, 'create'])->name('users.create');
    Route::post('/users',               [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit',    [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}',         [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}',      [UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{user}/toggle',[UserController::class, 'toggleStatus'])->name('users.toggle');

    // Roles Management
    Route::get('/roles',                [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create',         [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles',               [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}/edit',    [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}',         [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}',      [RoleController::class, 'destroy'])->name('roles.destroy');

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

        Route::get('/home-content', [HomePageContentController::class, 'edit'])->name('home-content.edit');
        Route::post('/home-content', [HomePageContentController::class, 'update'])->name('home-content.update');

        Route::get('/about-content', [AboutPageContentController::class, 'edit'])->name('about-content.edit');
        Route::post('/about-content', [AboutPageContentController::class, 'update'])->name('about-content.update');

        Route::get('/history-content', [HistoryPageContentController::class, 'edit'])->name('history-content.edit');
        Route::post('/history-content', [HistoryPageContentController::class, 'update'])->name('history-content.update');

        Route::get('/chairman-content', [ChairmanMessageContentController::class, 'edit'])->name('chairman-content.edit');
        Route::post('/chairman-content', [ChairmanMessageContentController::class, 'update'])->name('chairman-content.update');

        Route::get('/corporate-content', [CorporateBackgroundContentController::class, 'edit'])->name('corporate-content.edit');
        Route::post('/corporate-content', [CorporateBackgroundContentController::class, 'update'])->name('corporate-content.update');

        Route::get('/contact-content', [ContactPageContentController::class, 'edit'])->name('contact-content.edit');
        Route::post('/contact-content', [ContactPageContentController::class, 'update'])->name('contact-content.update');

        Route::get('/service-content', [App\Http\Controllers\Admin\WebsiteSettings\ServicePageContentController::class, 'edit'])->name('service-content.edit');
        Route::post('/service-content', [App\Http\Controllers\Admin\WebsiteSettings\ServicePageContentController::class, 'update'])->name('service-content.update');

        Route::resource('services', App\Http\Controllers\Admin\WebsiteSettings\ServiceController::class)->except(['show']);
        Route::patch('services/{service}/toggle', [App\Http\Controllers\Admin\WebsiteSettings\ServiceController::class, 'toggleStatus'])->name('services.toggle');

        // Mail / SMTP Settings
        Route::get('/mail',       [MailSettingController::class, 'edit'])->name('mail.edit');
        Route::post('/mail',      [MailSettingController::class, 'update'])->name('mail.update');
        Route::post('/mail/test', [MailSettingController::class, 'sendTest'])->name('mail.test');
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
});

require __DIR__.'/auth.php';
