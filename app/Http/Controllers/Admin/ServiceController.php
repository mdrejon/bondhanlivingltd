<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ServiceController extends Controller
{
    public function index(): Response
    {
        $services = Service::orderBy('sort_order')->orderBy('id')->get();

        return Inertia::render('Admin/Services/Index', [
            'services' => $services,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Services/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        foreach (['image', 'gallery_image_1', 'gallery_image_2'] as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store('services', 'public');
            }
        }

        $data['slug'] = $this->uniqueSlug($data['title']);

        Service::create($data);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service created successfully.');
    }

    public function edit(Service $service): Response
    {
        return Inertia::render('Admin/Services/Edit', [
            'service' => $service,
        ]);
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $data = $this->validated($request);

        foreach (['image', 'gallery_image_1', 'gallery_image_2'] as $field) {
            if ($request->hasFile($field)) {
                if ($service->$field) {
                    Storage::disk('public')->delete($service->$field);
                }
                $data[$field] = $request->file($field)->store('services', 'public');
            } else {
                unset($data[$field]);
            }
        }

        $data['slug'] = $this->uniqueSlug($data['title'], $service->id);

        $service->update($data);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        foreach (['image', 'gallery_image_1', 'gallery_image_2'] as $field) {
            if ($service->$field) {
                Storage::disk('public')->delete($service->$field);
            }
        }

        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Service deleted successfully.');
    }

    public function toggleStatus(Service $service): RedirectResponse
    {
        $service->update(['is_active' => !$service->is_active]);

        return back()->with('success', 'Service status updated.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title'          => 'required|string',
            'icon_svg'       => 'nullable|string',
            'image'          => 'nullable|image|mimes:jpeg,jpg,png,webp',
            'short_desc'     => 'nullable|string',
            'description'    => 'nullable|string',
            'benefits_title' => 'nullable|string',
            'benefits_text'  => 'nullable|string',
            'gallery_image_1'=> 'nullable|image|mimes:jpeg,jpg,png,webp',
            'gallery_image_2'=> 'nullable|image|mimes:jpeg,jpg,png,webp',
            'features'       => 'nullable|array',
            'features.*'     => 'nullable|string',
            'faqs'           => 'nullable|array',
            'faqs.*.question'=> 'nullable|string',
            'faqs.*.answer'  => 'nullable|string',
            'btn_text'       => 'nullable|string',
            'btn_url'        => 'nullable|string',
            'is_featured'    => 'boolean',
            'sort_order'     => 'integer|min:0',
            'is_active'      => 'boolean',
        ]);

        // Sanitise arrays — remove blank entries
        if (isset($data['features'])) {
            $data['features'] = array_values(array_filter($data['features']));
        }
        if (isset($data['faqs'])) {
            $data['faqs'] = array_values(
                array_filter($data['faqs'], fn($f) => !empty($f['question']) || !empty($f['answer']))
            );
        }

        return $data;
    }

    private function uniqueSlug(string $title, int $excludeId = 0): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i    = 1;

        while (Service::where('slug', $slug)->where('id', '!=', $excludeId)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
