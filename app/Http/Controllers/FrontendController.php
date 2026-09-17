<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index()
    {
        $sliders = \App\Models\Slider::active()->get();
        
        $keys = [
            'home_page_why_choose_us',
            'home_page_service',
            'home_page_cta',
            'home_page_team',
            'home_page_project',
            'home_page_achievements',
            'home_page_testimonial',
            'home_page_blog',
            'home_page_seo',
        ];
        
        $homeContent = [];
        foreach ($keys as $key) {
            $raw = \App\Models\GlobalSetting::get($key);
            $homeContent[$key] = $raw ? json_decode($raw, true) : null;
        }

        $services = \App\Models\Service::where('status', true)->get();
        $servicesByCategory = $services->groupBy('category');

        return view('frontend.pages.index', compact('sliders', 'homeContent', 'servicesByCategory'));
    }

    public function about()
    {
        $keys = [
            'about_page_hero',
            'about_page_company',
            'about_page_achievements',
            'about_page_mission',
            'about_page_process',
            'about_page_seo',
        ];
        
        $aboutContent = [];
        foreach ($keys as $key) {
            $raw = \App\Models\GlobalSetting::get($key);
            $aboutContent[$key] = $raw ? json_decode($raw, true) : null;
        }

        return view('frontend.pages.about', compact('aboutContent'));
    }

    public function history()
    {
        $keys = [
            'history_page_hero',
            'history_page_main',
            'history_page_timeline',
            'history_page_seo',
        ];
        
        $settings = \App\Models\GlobalSetting::whereIn('key', $keys)
            ->where('hotel_id', 0)
            ->pluck('value', 'key')
            ->toArray();

        $historyContent = [];
        foreach ($keys as $key) {
            $historyContent[$key] = isset($settings[$key]) ? json_decode($settings[$key], true) : null;
        }

        return view('frontend.pages.history', compact('historyContent'));
    }

    public function chairmanMessage()
    {
        $keys = [
            'chairman_page_hero',
            'chairman_page_main',
            'chairman_page_info',
            'chairman_page_seo',
        ];
        
        $settings = \App\Models\GlobalSetting::whereIn('key', $keys)
            ->where('hotel_id', 0)
            ->pluck('value', 'key')
            ->toArray();

        $chairmanContent = [];
        foreach ($keys as $key) {
            $chairmanContent[$key] = isset($settings[$key]) ? json_decode($settings[$key], true) : null;
        }

        return view('frontend.pages.chairman-message', compact('chairmanContent'));
    }

    public function corporateBackground()
    {
        $keys = [
            'corporate_page_hero',
            'corporate_page_main',
            'corporate_page_seo',
        ];
        
        $settings = \App\Models\GlobalSetting::whereIn('key', $keys)
            ->where('hotel_id', 0)
            ->pluck('value', 'key')
            ->toArray();

        $corporateContent = [];
        foreach ($keys as $key) {
            $corporateContent[$key] = isset($settings[$key]) ? json_decode($settings[$key], true) : null;
        }

        return view('frontend.pages.corporate-background', compact('corporateContent'));
    }

    public function projectList()
    {
        return view('frontend.pages.projects.index');
    }

    public function runningProjects()
    {
        return view('frontend.pages.projects.running');
    }

    public function completedProjects()
    {
        return view('frontend.pages.projects.completed');
    }

    public function upcomingProjects()
    {
        return view('frontend.pages.projects.upcoming');
    }

    public function projectDetails($slug = null)
    {
        return view('frontend.pages.projects.show');
    }

    public function serviceList()
    {
        $keys = [
            'service_page_hero',
            'service_page_seo',
        ];
        
        $settings = \App\Models\GlobalSetting::whereIn('key', $keys)
            ->pluck('value', 'key')
            ->toArray();

        $serviceContent = [];
        foreach ($keys as $key) {
            $serviceContent[$key] = isset($settings[$key]) ? json_decode($settings[$key], true) : null;
        }

        $services = \App\Models\Service::where('status', true)->get();

        return view('frontend.pages.services.index', compact('serviceContent', 'services'));
    }

    public function serviceDetails($slug = null)
    {
        if (!$slug) {
            abort(404);
        }

        $service = \App\Models\Service::where('slug', $slug)->where('status', true)->firstOrFail();
        $allServices = \App\Models\Service::where('status', true)->get();

        return view('frontend.pages.services.show', compact('service', 'allServices'));
    }

    public function featuresAmenities()
    {
        return view('frontend.pages.features-amenities');
    }

    public function gallery()
    {
        return view('frontend.pages.gallery');
    }

    public function team()
    {
        return view('frontend.pages.team');
    }

    public function blog()
    {
        return view('frontend.pages.blog');
    }

    public function loanCalculator()
    {
        return view('frontend.pages.loan-calculator');
    }

    public function termsAndConditions()
    {
        return view('frontend.pages.terms-condition');
    }

    public function contact()
    {
        $keys = [
            'contact_page_hero',
            'contact_page_info',
            'contact_page_map',
            'contact_page_seo',
        ];
        
        $settings = \App\Models\GlobalSetting::whereIn('key', $keys)
            ->where('hotel_id', 0)
            ->pluck('value', 'key')
            ->toArray();

        $contactContent = [];
        foreach ($keys as $key) {
            $contactContent[$key] = isset($settings[$key]) ? json_decode($settings[$key], true) : null;
        }

        return view('frontend.pages.contact', compact('contactContent'));
    }
}
