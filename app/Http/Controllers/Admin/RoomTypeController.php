<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomAmenity;
use App\Models\RoomType;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class RoomTypeController extends Controller
{
    public function index(): Response
    {
        $roomTypes = RoomType::withCount('rooms')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return Inertia::render('Admin/RoomTypes/Index', [
            'roomTypes' => $roomTypes,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/RoomTypes/Create', [
            'allAmenities' => RoomAmenity::active()->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (!$request->hasFile('feature_image')) {
            return back()->withErrors(['feature_image' => 'Feature image is required.'])->withInput();
        }

        $data = $this->validated($request);

        $data['slug'] = Str::slug($data['name']);
        $data['gallery_images'] = $this->handleGalleryUpload($request, []);

        if ($request->hasFile('feature_image')) {
            $data['feature_image'] = $request->file('feature_image')->store('room-types', 'public');
        }
        if ($request->hasFile('og_image')) {
            $data['og_image'] = $request->file('og_image')->store('room-types/seo', 'public');
        }

        // Auto-generate keywords if none provided
        if (empty($data['meta_keywords'])) {
            $data['meta_keywords'] = $this->autoKeywords(
                $data['name'] ?? '',
                $data['short_desc'] ?? '',
                $data['description'] ?? ''
            );
        }

        $roomType = RoomType::create($data);

        // Sync amenities pivot
        $amenityIds = $request->input('amenity_ids', []);
        $roomType->roomAmenities()->sync($amenityIds);

        // Keep the legacy JSON column in sync for blade templates
        if (!empty($amenityIds)) {
            $amenitiesJson = RoomAmenity::whereIn('id', $amenityIds)->get()
                ->map(fn($a) => ['label' => $a->name, 'icon_svg' => $a->icon_svg])
                ->values()
                ->toArray();
            $roomType->update(['amenities' => $amenitiesJson]);
        }

        return redirect()->route('admin.room-types.index')
            ->with('success', 'Room type created successfully.');
    }

    public function edit(RoomType $roomType): Response
    {
        return Inertia::render('Admin/RoomTypes/Edit', [
            'roomType'           => $roomType,
            'allAmenities'       => RoomAmenity::active()->orderBy('sort_order')->orderBy('id')->get(),
            'selectedAmenityIds' => $roomType->roomAmenities->pluck('id')->toArray(),
        ]);
    }

    public function update(Request $request, RoomType $roomType): RedirectResponse
    {
        $data = $this->validated($request, $roomType->id);

        $data['slug'] = Str::slug($data['name']);

        // Gallery: keep existing (minus removed) + add new
        $keepImages  = $request->input('existing_gallery', []);
        $newImages   = $this->handleGalleryUpload($request, []);
        $data['gallery_images'] = array_merge($keepImages, $newImages);

        // Delete removed gallery images from storage
        foreach (($roomType->gallery_images ?? []) as $img) {
            if (!in_array($img, $data['gallery_images'])) {
                Storage::disk('public')->delete($img);
            }
        }

        // Feature image: replace if new file uploaded, remove if flagged
        if ($request->hasFile('feature_image')) {
            if ($roomType->feature_image) {
                Storage::disk('public')->delete($roomType->feature_image);
            }
            $data['feature_image'] = $request->file('feature_image')->store('room-types', 'public');
        } elseif ($request->boolean('remove_feature_image')) {
            if ($roomType->feature_image) {
                Storage::disk('public')->delete($roomType->feature_image);
            }
            $data['feature_image'] = null;
        }

        // OG image: replace if new file uploaded, remove if flagged
        $ogManuallyUploaded = $request->hasFile('og_image');
        if ($ogManuallyUploaded) {
            if ($roomType->og_image) {
                Storage::disk('public')->delete($roomType->og_image);
            }
            $data['og_image'] = $request->file('og_image')->store('room-types/seo', 'public');
        } elseif ($request->boolean('remove_og_image')) {
            if ($roomType->og_image) {
                Storage::disk('public')->delete($roomType->og_image);
            }
            $data['og_image'] = null;
        }

        // Auto-generate keywords if none provided
        if (empty($data['meta_keywords'])) {
            $data['meta_keywords'] = $this->autoKeywords(
                $data['name'] ?? '',
                $data['short_desc'] ?? '',
                $data['description'] ?? ''
            );
        }

        $roomType->update($data);

        // Sync amenities pivot
        $amenityIds = $request->input('amenity_ids', []);
        $roomType->roomAmenities()->sync($amenityIds);

        // Keep the legacy JSON column in sync for blade templates
        if (!empty($amenityIds)) {
            $amenitiesJson = RoomAmenity::whereIn('id', $amenityIds)->get()
                ->map(fn($a) => ['label' => $a->name, 'icon_svg' => $a->icon_svg])
                ->values()
                ->toArray();
            $roomType->update(['amenities' => $amenitiesJson]);
        } else {
            $roomType->update(['amenities' => []]);
        }

        return redirect()->route('admin.room-types.index')
            ->with('success', 'Room type updated successfully.');
    }

    public function destroy(RoomType $roomType): RedirectResponse
    {
        foreach (($roomType->gallery_images ?? []) as $img) {
            Storage::disk('public')->delete($img);
        }
        if ($roomType->feature_image) {
            Storage::disk('public')->delete($roomType->feature_image);
        }
        if ($roomType->og_image) {
            Storage::disk('public')->delete($roomType->og_image);
        }

        $roomType->delete();

        return back()->with('success', 'Room type deleted.');
    }

    public function toggleStatus(RoomType $roomType): RedirectResponse
    {
        $roomType->update(['is_active' => !$roomType->is_active]);

        return back()->with('success', 'Status updated.');
    }

    public function deleteGalleryImage(Request $request, RoomType $roomType): RedirectResponse
    {
        $path   = $request->validate(['path' => 'required|string'])['path'];
        $images = $roomType->gallery_images ?? [];

        if (in_array($path, $images)) {
            Storage::disk('public')->delete($path);
            $roomType->update([
                'gallery_images' => array_values(array_filter($images, fn($img) => $img !== $path)),
            ]);
        }

        return back()->with('success', 'Image removed.');
    }

    private function handleGalleryUpload(Request $request, array $existing): array
    {
        $paths = $existing;
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $paths[] = $file->store('room-types', 'public');
            }
        }
        return $paths;
    }

    /** Extract auto-keywords from text fields, skipping common stop-words. */
    private function autoKeywords(string ...$texts): string
    {
        static $stop = [
            'a','an','the','and','or','but','in','on','at','to','for','of','with',
            'by','from','as','is','was','are','were','be','been','being','have',
            'has','had','do','does','did','will','would','could','should','may',
            'might','can','this','that','these','those','it','its','we','our',
            'you','your','hotel','beach','way','room','cox','bazar',
        ];
        $text  = implode(' ', $texts);
        $words = preg_split('/\W+/u', mb_strtolower($text), -1, PREG_SPLIT_NO_EMPTY);
        $words = array_filter($words, fn($w) => mb_strlen($w) > 3 && !in_array($w, $stop, true));
        return implode(', ', array_slice(array_unique(array_values($words)), 0, 12));
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name'             => 'required|string',
            'short_desc'       => 'nullable|string',
            'description'      => 'nullable|string',
            'price'               => 'required|numeric|min:0',
            'price_usd'           => 'nullable|numeric|min:0',
            'discount_type_bdt'   => 'nullable|in:percentage,fixed',
            'discount_value_bdt'  => 'nullable|numeric|min:0',
            'discount_type_usd'   => 'nullable|in:percentage,fixed',
            'discount_value_usd'  => 'nullable|numeric|min:0',
            'offer_expires_at'    => 'nullable|date',
            'price_unit'          => 'required|string',
            'check_in_time'    => 'nullable|string',
            'check_out_time'   => 'nullable|string',
            'max_adults'       => 'required|integer|min:1',
            'max_children'     => 'required|integer|min:0',
            'bed_type'         => 'nullable|string',
            'amenity_ids'      => 'nullable|array',
            'amenity_ids.*'    => 'integer|exists:room_amenities,id',
            'amenities'        => 'nullable|array',
            'amenities.*.icon_svg' => 'nullable|string',
            'amenities.*.label'    => 'nullable|string',
            'features'         => 'nullable|array',
            'features.*'       => 'nullable|string',
            'room_rules'       => 'nullable|array',
            'room_rules.*'     => 'nullable|string',
            'gallery_images'   => 'nullable|array',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,jpg,png,webp',
            'feature_image'    => 'nullable|image|mimes:jpeg,jpg,png,webp',
            'rating'           => 'nullable|numeric|min:0',
            'is_featured'      => 'boolean',
            'sort_order'       => 'integer|min:0',
            'is_active'        => 'boolean',
            'meta_title'       => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keywords'    => 'nullable|string',
            'og_image'         => 'nullable|image|mimes:jpeg,jpg,png,webp',
        ]);
    }
}
