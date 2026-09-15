<?php

namespace App\Http\Controllers;

use App\Mail\InquiryConfirmationMail;
use App\Mail\InquiryNotificationMail;
use App\Models\Facility;
use App\Models\Faq;
use App\Models\GalleryImage;
use App\Models\GlobalSetting;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\Inquiry;
use App\Models\RoomType;
use App\Models\Service;
use App\Models\Slider;
use App\Models\Testimonial;
use App\Support\EmailNotificationSettings;
use App\Support\SpamGuard;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class FrontendController extends Controller
{
    public function home(): View
    {
        return view('frontend.home', [
            'sliders'       => Slider::where('is_active', true)->orderBy('sort_order')->get(),
            'featuredRooms' => RoomType::active()->where('is_featured', true)->take(6)->get(),
            'allRooms'      => RoomType::active()->take(6)->get(),
            'services'      => Service::active()->take(6)->get(),
            'gallery'       => GalleryImage::where('is_active', true)->orderBy('sort_order')->take(9)->get(),
            'testimonials'  => Testimonial::where('is_active', true)->orderBy('sort_order')->get(),
            'faqs'          => Faq::forPage('home')->take(8)->get(),
            'settings'      => GlobalSetting::allAsArray(),
            'latestBlogs'   => Blog::published()->orderByDesc('published_at')->take(5)->get(),
        ]);
    }

    public function rooms(): View
    {
        return view('frontend.rooms', [
            'rooms'    => RoomType::active()->get(),
            'settings' => GlobalSetting::allAsArray(),
        ]);
    }

    public function roomDetail(string $slug): View
    {
        $room = RoomType::where('slug', $slug)->where('is_active', true)->firstOrFail();

        return view('frontend.room-detail', [
            'room'      => $room,
            'related'   => RoomType::active()->where('id', '!=', $room->id)->get(),
            'allRooms'  => RoomType::active()->get(),
            'settings'  => GlobalSetting::allAsArray(),
        ]);
    }

    public function about(): View
    {
        return view('frontend.about', [
            'settings' => GlobalSetting::allAsArray(),
            'faqs'     => Faq::forPage('about')->get(),
        ]);
    }

    public function history(): View
    {
        $settings = GlobalSetting::allAsArray();

        $timeline = [];
        if (!empty($settings['hist_timeline'])) {
            $decoded = json_decode($settings['hist_timeline'], true);
            $timeline = is_array($decoded) ? $decoded : [];
        }

        return view('frontend.history', compact('settings', 'timeline'));
    }

    public function services(): View
    {
        return view('frontend.services', [
            'services' => Service::active()->get(),
            'settings' => GlobalSetting::allAsArray(),
        ]);
    }

    public function serviceDetail(string $slug): View
    {
        $service = Service::where('slug', $slug)->where('is_active', true)->firstOrFail();

        return view('frontend.service-detail', [
            'service'     => $service,
            'allServices' => Service::active()->get(),
            'settings'    => GlobalSetting::allAsArray(),
        ]);
    }

    public function gallery(): View
    {
        return view('frontend.gallery', [
            'images'   => GalleryImage::where('is_active', true)->orderBy('sort_order')->get(),
            'settings' => GlobalSetting::allAsArray(),
        ]);
    }

    public function faqs(): View
    {
        return view('frontend.faqs', [
            'faqs'     => Faq::where('page', 'faq')->where('is_active', true)->orderBy('sort_order')->get(),
            'settings' => GlobalSetting::allAsArray(),
        ]);
    }

    public function facilities(): View
    {
        return view('frontend.facilities', [
            'facilities' => Facility::active()->get(),
            'settings'   => GlobalSetting::allAsArray(),
        ]);
    }

    public function booking(): View
    {
        return view('frontend.booking', [
            'rooms'    => RoomType::active()->orderBy('sort_order')->get(),
            'settings' => GlobalSetting::allAsArray(),
        ]);
    }

    public function contact(): View
    {
        return view('frontend.contact', [
            'settings' => GlobalSetting::allAsArray(),
        ]);
    }

    public function submitContact(Request $request): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'name'    => 'required|string|max:150',
            'email'   => 'required|email|max:150',
            'phone'   => 'nullable|string|max:30',
            'subject' => 'nullable|string|max:200',
            'message' => 'required|string|max:2000',
        ]);

        $message = 'Thank you! Your message has been sent. We will get back to you within 24 hours.';

        if ($reason = SpamGuard::reason($request)) {
            Log::info('Blocked spam contact submission', ['reason' => $reason, 'ip' => $request->ip(), 'email' => $data['email']]);

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => $message]);
            }

            return back()->with('success', $message);
        }

        $inquiry = Inquiry::create([
            'type'       => Inquiry::TYPE_CONTACT_PAGE,
            'name'       => $data['name'],
            'email'      => $data['email'],
            'phone'      => $data['phone'] ?? null,
            'subject'    => $data['subject'] ?? null,
            'message'    => $data['message'],
            'ip_address' => $request->ip(),
        ]);

        $this->sendInquiryEmails($inquiry);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return back()->with('success', $message);
    }

    public function submitInquiry(Request $request): JsonResponse
    {
        $data = $request->validate([
            'type'    => 'required|in:quote,contact_widget',
            'name'    => 'required|string|max:150',
            'email'   => 'required|email|max:150',
            'message' => 'required|string|max:2000',
        ]);

        $message = 'Thank you! We have received your message and will get back to you within 24 hours.';

        if ($reason = SpamGuard::reason($request)) {
            Log::info('Blocked spam inquiry submission', ['reason' => $reason, 'ip' => $request->ip(), 'email' => $data['email']]);

            return response()->json(['success' => true, 'message' => $message]);
        }

        $inquiry = Inquiry::create([
            'type'       => $data['type'],
            'name'       => $data['name'],
            'email'      => $data['email'],
            'message'    => $data['message'],
            'ip_address' => $request->ip(),
        ]);

        $this->sendInquiryEmails($inquiry);

        return response()->json(['success' => true, 'message' => $message]);
    }

    public function blog(Request $request): View
    {
        $query = Blog::published()->with('category')->orderByDesc('published_at');

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('tag')) {
            $query->whereJsonContains('tags', $request->tag);
        }

        $blogs      = $query->paginate(9)->withQueryString();
        $categories = BlogCategory::active()->withCount(['blogs' => fn($q) => $q->published()])->get();
        $recentPosts = Blog::published()->orderByDesc('published_at')->take(5)->get(['id', 'title', 'slug', 'feature_image', 'published_at']);
        $popularTags = Blog::published()->get('tags')->flatMap(fn($b) => $b->tags ?? [])->countBy()->sortDesc()->take(20)->keys()->all();
        $settings    = GlobalSetting::allAsArray();

        return view('frontend.blog', compact('blogs', 'categories', 'recentPosts', 'popularTags', 'settings'));
    }

    public function blogDetail(string $slug): View
    {
        $blog = Blog::published()->where('slug', $slug)->with(['category', 'approvedComments.replies'])->firstOrFail();
        $blog->increment('view_count');

        $prev = Blog::published()->where('published_at', '<', $blog->published_at)->orderByDesc('published_at')->first(['id', 'title', 'slug']);
        $next = Blog::published()->where('published_at', '>', $blog->published_at)->orderBy('published_at')->first(['id', 'title', 'slug']);

        $recentPosts = Blog::published()->where('id', '!=', $blog->id)->orderByDesc('published_at')->take(5)->get(['id', 'title', 'slug', 'feature_image', 'published_at']);
        $categories  = BlogCategory::active()->withCount(['blogs' => fn($q) => $q->published()])->get();
        $popularTags = Blog::published()->get('tags')->flatMap(fn($b) => $b->tags ?? [])->countBy()->sortDesc()->take(20)->keys()->all();

        return view('frontend.blog-detail', compact('blog', 'prev', 'next', 'recentPosts', 'categories', 'popularTags'));
    }

    public function submitBlogComment(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'blog_id'   => 'required|exists:blogs,id',
            'parent_id' => 'nullable|exists:blog_comments,id',
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|max:150',
            'message'   => 'required|string|max:2000',
        ]);

        $message = 'Thank you! Your comment is awaiting moderation.';

        if ($reason = SpamGuard::reason($request)) {
            Log::info('Blocked spam blog comment', ['reason' => $reason, 'ip' => $request->ip(), 'email' => $data['email']]);

            return back()->with('comment_success', $message);
        }

        BlogComment::create([...$data, 'is_approved' => false]);

        return back()->with('comment_success', $message);
    }

    private function sendInquiryEmails(Inquiry $inquiry): void
    {
        try {
            // Always this inquiry's own hotel's SMTP config, not just whichever
            // hotel is ambient — see EmailNotificationSettings::applyMailConfigFor().
            EmailNotificationSettings::applyMailConfigFor($inquiry->hotel_id);

            if (EmailNotificationSettings::enabled('email_toggle_new_inquiry_customer', true)) {
                Mail::to($inquiry->email)->send(new InquiryConfirmationMail($inquiry));
            }
            if (EmailNotificationSettings::enabled('email_toggle_new_inquiry_admin', true)) {
                EmailNotificationSettings::sendToAdmins(fn () => new InquiryNotificationMail($inquiry), 'Inquiry notification');
            }
        } catch (\Throwable $e) {
            Log::error('Inquiry email failed for inquiry #' . $inquiry->id . ': ' . $e->getMessage());
        }
    }
}
