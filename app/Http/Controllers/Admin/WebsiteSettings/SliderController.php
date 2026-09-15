<?php

namespace App\Http\Controllers\Admin\WebsiteSettings;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class SliderController extends Controller
{
    public function index(): Response
    {
        $sliders = Slider::orderBy('sort_order')->orderBy('id')->get();

        return Inertia::render('Admin/WebsiteSettings/HeroSlider/Index', [
            'sliders' => $sliders,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/WebsiteSettings/HeroSlider/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'label'            => 'nullable|string',
            'title'            => 'required|string',
            'subtitle'         => 'nullable|string',
            'description'      => 'nullable|string',
            'button_text'      => 'required|string',
            'button_url'       => 'required|string',
            'star_label'       => 'nullable|string',
            'star_rating'      => 'nullable|integer|min:0|max:5',
            'sort_order'       => 'integer|min:0',
            'is_active'        => 'boolean',
            'background_image' => 'nullable|image|mimes:jpeg,jpg,png,webp',
        ]);

        if ($request->hasFile('background_image')) {
            $data['background_image'] = $request->file('background_image')
                ->store('sliders', 'public');
        }

        Slider::create($data);

        return redirect()->route('admin.website-settings.sliders.index')
            ->with('success', 'Slide created successfully.');
    }

    public function edit(Slider $slider): Response
    {
        return Inertia::render('Admin/WebsiteSettings/HeroSlider/Edit', [
            'slider' => $slider,
        ]);
    }

    public function update(Request $request, Slider $slider): RedirectResponse
    {
        $data = $request->validate([
            'label'            => 'nullable|string',
            'title'            => 'required|string',
            'subtitle'         => 'nullable|string',
            'description'      => 'nullable|string',
            'button_text'      => 'required|string',
            'button_url'       => 'required|string',
            'star_label'       => 'nullable|string',
            'star_rating'      => 'nullable|integer|min:0|max:5',
            'sort_order'       => 'integer|min:0',
            'is_active'        => 'boolean',
            'background_image' => 'nullable|image|mimes:jpeg,jpg,png,webp',
        ]);

        if ($request->hasFile('background_image')) {
            if ($slider->background_image) {
                Storage::disk('public')->delete($slider->background_image);
            }
            $data['background_image'] = $request->file('background_image')
                ->store('sliders', 'public');
        } else {
            unset($data['background_image']);
        }

        $slider->update($data);

        return redirect()->route('admin.website-settings.sliders.index')
            ->with('success', 'Slide updated successfully.');
    }

    public function destroy(Slider $slider): RedirectResponse
    {
        if ($slider->background_image) {
            Storage::disk('public')->delete($slider->background_image);
        }

        $slider->delete();

        return redirect()->route('admin.website-settings.sliders.index')
            ->with('success', 'Slide deleted successfully.');
    }

    /** Toggle active status via PATCH. */
    public function toggleStatus(Slider $slider): RedirectResponse
    {
        $slider->update(['is_active' => !$slider->is_active]);

        return back()->with('success', 'Slide status updated.');
    }
}
