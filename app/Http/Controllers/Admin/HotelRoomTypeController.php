<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\RoomType;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class HotelRoomTypeController extends Controller
{
    public function index(Hotel $hotel): View
    {
        $roomTypes = $hotel->roomTypes()->latest()->paginate(15);

        return view('admin.hotels.room-types.index', compact('hotel', 'roomTypes'));
    }

    public function create(Hotel $hotel): View
    {
        return view('admin.hotels.room-types.create', compact('hotel'));
    }

    public function store(Request $request, Hotel $hotel): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $validated['hotel_id'] = $hotel->id;
        $validated['is_refundable'] = $request->boolean('is_refundable');
        $validated['capacity_children'] = $validated['capacity_children'] ?? 0;
        $validated['slug'] = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);

        // Handle Main Image Upload (Convert to WebP)
        if ($request->hasFile('image_file')) {
            $validated['image'] = ImageService::uploadAsWebp($request->file('image_file'), 'room_types');
        } elseif ($request->filled('image')) {
            $validated['image'] = $request->image;
        }

        // Handle Gallery Uploads (Convert to WebP) & Manual lines
        $gallery = [];
        if (!empty($request->gallery_text)) {
            $gallery = array_values(array_filter(array_map('trim', explode("\n", (string) $request->gallery_text))));
        }

        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $file) {
                $gallery[] = ImageService::uploadAsWebp($file, 'room_types/gallery');
            }
        }

        $validated['gallery'] = array_values(array_unique(array_filter($gallery)));

        // Process highlights string to array if provided
        if (!empty($request->highlights_text)) {
            $validated['highlights'] = array_values(array_filter(array_map('trim', explode("\n", $request->highlights_text))));
        }

        $roomType = RoomType::create($validated);

        if ($request->hasFile('image_file')) {
            $roomType->addMediaFromRequest('image_file')->toMediaCollection('thumbnail');
        }
        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $file) {
                $roomType->addMedia($file)->toMediaCollection('gallery');
            }
        }

        clear_api_cache();

        return redirect()->route('admin.hotels.room-types.index', $hotel)->with('success', 'Room type created successfully.');
    }

    public function edit(Hotel $hotel, RoomType $roomType): View
    {
        abort_unless($roomType->hotel_id === $hotel->id, 404);

        return view('admin.hotels.room-types.edit', compact('hotel', 'roomType'));
    }

    public function update(Request $request, Hotel $hotel, RoomType $roomType): RedirectResponse
    {
        abort_unless($roomType->hotel_id === $hotel->id, 404);

        $validated = $request->validate($this->rules());
        $validated['is_refundable'] = $request->boolean('is_refundable');
        $validated['capacity_children'] = $validated['capacity_children'] ?? 0;
        $validated['slug'] = !empty($validated['slug']) ? Str::slug($validated['slug']) : ($roomType->slug ?: Str::slug($validated['name']));

        // Handle Main Image removal or replacement (Convert to WebP)
        if ($request->boolean('remove_main_image')) {
            ImageService::deleteFile($roomType->image);
            $validated['image'] = null;
            if ($roomType->hasMedia('thumbnail')) {
                $roomType->clearMediaCollection('thumbnail');
            }
        } elseif ($request->hasFile('image_file')) {
            ImageService::deleteFile($roomType->image);
            $validated['image'] = ImageService::uploadAsWebp($request->file('image_file'), 'room_types');
            $roomType->addMediaFromRequest('image_file')->toMediaCollection('thumbnail');
        } elseif ($request->filled('image')) {
            $validated['image'] = $request->image;
        }

        // Handle Gallery
        $currentGallery = is_array($roomType->gallery) ? $roomType->gallery : (json_decode($roomType->gallery ?? '[]', true) ?: []);
        
        $toRemove = $request->input('remove_gallery', []);
        if (!is_array($toRemove)) {
            $toRemove = [];
        }
        $toRemove = array_values(array_filter(array_map('trim', $toRemove)));

        // Remove deleted files from storage if stored locally
        foreach ($toRemove as $removePath) {
            ImageService::deleteFile($removePath);
        }

        // Filter out removed photos from current gallery
        if (!empty($toRemove)) {
            $currentGallery = array_values(array_filter($currentGallery, function ($photo) use ($toRemove) {
                return !in_array(trim($photo), $toRemove, true);
            }));
        }

        // Add newly uploaded gallery photos (Convert to WebP)
        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $file) {
                $currentGallery[] = ImageService::uploadAsWebp($file, 'room_types/gallery');
                $roomType->addMedia($file)->toMediaCollection('gallery');
            }
        }

        // Merge manual gallery text if provided, excluding any items marked for removal
        if ($request->filled('gallery_text')) {
            $manualLines = array_values(array_filter(array_map('trim', explode("\n", (string) $request->gallery_text))));
            if (!empty($toRemove)) {
                $manualLines = array_values(array_filter($manualLines, function ($photo) use ($toRemove) {
                    return !in_array(trim($photo), $toRemove, true);
                }));
            }
            $currentGallery = array_merge($currentGallery, $manualLines);
        }

        $validated['gallery'] = array_values(array_unique(array_filter($currentGallery)));

        // Process highlights string to array if provided
        if ($request->has('highlights_text')) {
            $validated['highlights'] = array_values(array_filter(array_map('trim', explode("\n", (string) $request->highlights_text))));
        }

        $roomType->update($validated);

        clear_api_cache();

        return redirect()->route('admin.hotels.room-types.index', $hotel)->with('success', 'Room type updated successfully.');
    }

    public function destroy(Hotel $hotel, RoomType $roomType): RedirectResponse
    {
        abort_unless($roomType->hotel_id === $hotel->id, 404);

        $roomType->delete();

        clear_api_cache();

        return redirect()->route('admin.hotels.room-types.index', $hotel)->with('success', 'Room type deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:150'],
            'subtitle' => ['nullable', 'string', 'max:200'],
            'tag' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'long_description' => ['nullable', 'string'],
            'floor' => ['nullable', 'string', 'max:100'],
            'bed_type' => ['nullable', 'string', 'max:100'],
            'room_size' => ['nullable', 'string', 'max:100'],
            'stars' => ['nullable', 'integer', 'min:1', 'max:5'],
            'image' => ['nullable', 'string', 'max:500'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,avif,gif', 'max:8192'],
            'gallery_files' => ['nullable', 'array'],
            'gallery_files.*' => ['image', 'mimes:jpeg,png,jpg,webp,avif,gif', 'max:8192'],
            'remove_gallery' => ['nullable', 'array'],
            'remove_main_image' => ['nullable', 'boolean'],
            'capacity_adults' => ['required', 'integer', 'min:1'],
            'capacity_children' => ['nullable', 'integer', 'min:0'],
            'total_rooms' => ['required', 'integer', 'min:0'],
            'available_rooms' => ['required', 'integer', 'min:0'],
            'base_price_per_night' => ['required', 'numeric', 'min:0'],
            'cancellation_policy' => ['nullable', 'string'],
        ];
    }
}
