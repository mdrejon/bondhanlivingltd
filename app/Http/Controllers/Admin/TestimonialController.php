<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GlobalSetting;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class TestimonialController extends Controller
{
    private array $sectionKeys = [
        'testi_badge', 'testi_title', 'testi_image', 'testi_image_alt',
    ];

    public function index(): Response
    {
        $settings = GlobalSetting::whereIn('key', $this->sectionKeys)
            ->pluck('value', 'key')
            ->toArray();

        foreach ($this->sectionKeys as $key) {
            $settings[$key] ??= null;
        }

        return Inertia::render('Admin/Testimonials/Index', [
            'testimonials' => Testimonial::orderBy('sort_order')->orderBy('id')->get(),
            'settings'     => $settings,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('testimonials', 'public');
        }

        Testimonial::create($data);

        return back()->with('success', 'Testimonial added successfully.');
    }

    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('avatar')) {
            if ($testimonial->avatar) {
                Storage::disk('public')->delete($testimonial->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('testimonials', 'public');
        } else {
            unset($data['avatar']);
        }

        $testimonial->update($data);

        return back()->with('success', 'Testimonial updated.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        if ($testimonial->avatar) {
            Storage::disk('public')->delete($testimonial->avatar);
        }
        $testimonial->delete();

        return back()->with('success', 'Testimonial deleted.');
    }

    public function toggleStatus(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update(['is_active' => !$testimonial->is_active]);

        return back()->with('success', 'Status updated.');
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'testi_badge'     => 'nullable|string',
            'testi_title'     => 'nullable|string',
            'testi_image'     => 'nullable|image|mimes:jpeg,jpg,png,webp',
            'testi_image_alt' => 'nullable|string',
        ]);

        if ($request->hasFile('testi_image')) {
            $existing = GlobalSetting::get('testi_image');
            if ($existing) {
                Storage::disk('public')->delete($existing);
            }
            $data['testi_image'] = $request->file('testi_image')->store('settings', 'public');
        } else {
            unset($data['testi_image']);
        }

        GlobalSetting::setMany($data);

        return back()->with('success', 'Section settings saved.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'review'     => 'required|string',
            'name'       => 'required|string',
            'role'       => 'nullable|string',
            'avatar'     => 'nullable|image|mimes:jpeg,jpg,png,webp',
            'rating'     => 'required|numeric|min:1',
            'sort_order' => 'integer|min:0',
            'is_active'  => 'boolean',
        ]);
    }
}
