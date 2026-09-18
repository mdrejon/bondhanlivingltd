<?php

namespace App\Http\Controllers\Admin\WebsiteSettings;

use App\Http\Controllers\Controller;
use App\Models\GlobalSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class FooterSettingController extends Controller
{
    public function edit()
    {
        $settings = [
            'footer_logo' => GlobalSetting::get('footer_logo', ''),
            'footer_partners' => json_decode(GlobalSetting::get('footer_partners', '[]'), true) ?? [],
            
            'footer_newsletter_subtitle' => GlobalSetting::get('footer_newsletter_subtitle', 'READY FOR A SUBSCRIPTION?'),
            'footer_newsletter_title' => GlobalSetting::get('footer_newsletter_title', 'Subcribe Our Latest News'),
            
            'footer_about_text' => GlobalSetting::get('footer_about_text', 'Bondhan Living Ltd is a trusted real estate and construction company based in Chittagong, committed to building quality homes and commercial spaces across Bangladesh.'),
            'social_facebook' => GlobalSetting::get('social_facebook', 'https://www.facebook.com/bondhanlivingltd/'),
            'social_twitter' => GlobalSetting::get('social_twitter', 'https://www.twitter.com/'),
            'social_linkedin' => GlobalSetting::get('social_linkedin', 'https://www.linkedin.com/'),
            'footer_whatsapp' => GlobalSetting::get('footer_whatsapp', 'https://www.whatsapp.com/'),
            
            'footer_quick_links' => json_decode(GlobalSetting::get('footer_quick_links', '[]'), true) ?? [],
            
            'footer_address' => GlobalSetting::get('footer_address', 'House No. 18/B, (1st Floor) Mehedibag Road, Chittagong'),
            'footer_email' => GlobalSetting::get('footer_email', 'info@bondhanlivingltd.com'),
            'footer_phone' => GlobalSetting::get('footer_phone', '+8801740 574 490'),
            
            'footer_gallery' => json_decode(GlobalSetting::get('footer_gallery', '[]'), true) ?? [],
            
            'footer_copyright' => GlobalSetting::get('footer_copyright', '© 2026 Bondhan Living Limited. All Rights Reserved. | Design & Development by Wexnix Technologies Ltd.'),
            'footer_privacy_url' => GlobalSetting::get('footer_privacy_url', '#'),
            'footer_terms_url' => GlobalSetting::get('footer_terms_url', '#'),
        ];

        return Inertia::render('Admin/WebsiteSettings/Footer/Edit', [
            'settings' => $settings
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'footer_newsletter_subtitle' => 'nullable|string|max:255',
            'footer_newsletter_title' => 'nullable|string|max:255',
            'footer_about_text' => 'nullable|string|max:1000',
            'social_facebook' => 'nullable|string|max:255',
            'social_twitter' => 'nullable|string|max:255',
            'social_linkedin' => 'nullable|string|max:255',
            'footer_whatsapp' => 'nullable|string|max:255',
            'footer_address' => 'nullable|string|max:255',
            'footer_email' => 'nullable|string|max:255',
            'footer_phone' => 'nullable|string|max:255',
            'footer_copyright' => 'nullable|string|max:255',
            'footer_privacy_url' => 'nullable|string|max:255',
            'footer_terms_url' => 'nullable|string|max:255',
        ]);

        GlobalSetting::setMany($validated);

        // Upload Footer Logo
        if ($request->hasFile('footer_logo')) {
            $oldImage = GlobalSetting::get('footer_logo');
            if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }
            $path = $request->file('footer_logo')->store('settings/footer', 'public');
            GlobalSetting::set('footer_logo', $path);
        }

        // Quick Links (JSON)
        if ($request->has('footer_quick_links')) {
            $links = is_string($request->footer_quick_links) ? json_decode($request->footer_quick_links, true) : $request->footer_quick_links;
            GlobalSetting::set('footer_quick_links', json_encode($links ?? []));
        }

        // Partners (JSON + Files)
        if ($request->has('footer_partners')) {
            $partners = $request->footer_partners;
            if (is_string($partners)) {
                $partners = json_decode($partners, true);
            }
            
            $processedPartners = [];
            
            if (is_array($partners)) {
                foreach ($partners as $index => $partner) {
                    $imagePath = $partner['image'] ?? null;
                    
                    // Handle file upload
                    $fileKey = "footer_partners_{$index}_image";
                    if ($request->hasFile($fileKey)) {
                        $imagePath = $request->file($fileKey)->store('settings/partners', 'public');
                    }
                    
                    if ($imagePath || !empty($partner['url'])) {
                        $processedPartners[] = [
                            'image' => $imagePath,
                            'url' => $partner['url'] ?? '#'
                        ];
                    }
                }
            }
            GlobalSetting::set('footer_partners', json_encode($processedPartners));
        }
        
        // Gallery (JSON + Files)
        if ($request->has('footer_gallery')) {
            $gallery = $request->footer_gallery;
            if (is_string($gallery)) {
                $gallery = json_decode($gallery, true);
            }
            
            $processedGallery = [];
            
            if (is_array($gallery)) {
                foreach ($gallery as $index => $item) {
                    $imagePath = $item['image'] ?? null;
                    
                    // Handle file upload
                    $fileKey = "footer_gallery_{$index}_image";
                    if ($request->hasFile($fileKey)) {
                        $imagePath = $request->file($fileKey)->store('settings/footer/gallery', 'public');
                    }
                    
                    if ($imagePath) {
                        $processedGallery[] = [
                            'image' => $imagePath
                        ];
                    }
                }
            }
            GlobalSetting::set('footer_gallery', json_encode($processedGallery));
        }

        return back()->with('success', 'Footer settings updated successfully.');
    }
}
