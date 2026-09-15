<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\GlobalSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FacilityController extends Controller
{
    private array $sectionKeys = [
        'fac_badge', 'fac_title',
        'fac_hero_image', 'fac_hero_title',
        'fac_seo_title', 'fac_seo_description', 'fac_seo_keywords', 'fac_seo_og_image',
    ];

    public function index(): Response
    {
        $settings = GlobalSetting::whereIn('key', $this->sectionKeys)
            ->pluck('value', 'key')
            ->toArray();

        foreach ($this->sectionKeys as $key) {
            $settings[$key] ??= null;
        }

        return Inertia::render('Admin/Facilities/Index', [
            'facilities' => Facility::orderBy('sort_order')->orderBy('id')->get(),
            'settings'   => $settings,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        Facility::create($data);

        return back()->with('success', 'Facility card added successfully.');
    }

    public function update(Request $request, Facility $facility): RedirectResponse
    {
        $facility->update($this->validated($request));

        return back()->with('success', 'Facility card updated.');
    }

    public function destroy(Facility $facility): RedirectResponse
    {
        $facility->delete();

        return back()->with('success', 'Facility card deleted.');
    }

    public function toggleStatus(Facility $facility): RedirectResponse
    {
        $facility->update(['is_active' => !$facility->is_active]);

        return back()->with('success', 'Status updated.');
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'fac_badge'           => 'nullable|string',
            'fac_title'           => 'nullable|string',
            'fac_hero_title'      => 'nullable|string',
            'fac_seo_title'       => 'nullable|string',
            'fac_seo_description' => 'nullable|string',
            'fac_seo_keywords'    => 'nullable|string',
            'fac_hero_image'      => 'nullable|image',
            'fac_seo_og_image'    => 'nullable|image',
        ]);

        foreach (['fac_hero_image', 'fac_seo_og_image'] as $imgKey) {
            if ($request->hasFile($imgKey)) {
                $data[$imgKey] = $request->file($imgKey)->store('settings', 'public');
            } else {
                unset($data[$imgKey]);
            }
        }

        GlobalSetting::setMany($data);

        return back()->with('success', 'Settings saved.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title'      => 'required|string',
            'icon_svg'   => 'nullable|string',
            'items'      => 'required|array|min:1',
            'items.*'    => 'required|string',
            'sort_order' => 'integer|min:0',
            'is_active'  => 'boolean',
        ]);

        // Strip blank items
        $data['items'] = array_values(array_filter($data['items'], fn($v) => trim($v) !== ''));

        return $data;
    }
}
