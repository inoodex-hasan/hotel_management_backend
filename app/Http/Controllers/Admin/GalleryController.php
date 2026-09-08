<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use App\Models\Hotel;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(Request $request): View
    {
        $query = GalleryItem::query()->with('hotel');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category') && strtolower($request->category) !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('hotel_id')) {
            $query->where('hotel_id', $request->hotel_id);
        }

        $items = $query->orderBy('sort_order')
            ->orderBy('id', 'asc')
            ->paginate(15)
            ->withQueryString();

        $categories = GalleryItem::distinct()->pluck('category')->filter()->values();
        $hotels = Hotel::all();

        return view('admin.gallery.index', compact('items', 'categories', 'hotels'));
    }

    public function create(): View
    {
        $hotels = Hotel::all();
        $item = new GalleryItem([
            'is_active' => true,
            'sort_order' => (GalleryItem::max('sort_order') ?? 0) + 1,
            'category' => 'Interior',
        ]);
        $existingCategories = GalleryItem::distinct()->pluck('category')->filter()->values();

        return view('admin.gallery.create', compact('hotels', 'item', 'existingCategories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        if (empty($validated['hotel_id'])) {
            $defaultHotel = Hotel::first();
            $validated['hotel_id'] = $defaultHotel?->id;
        }

        if ($request->hasFile('image_file')) {
            $validated['image'] = ImageService::uploadAsWebp($request->file('image_file'), 'gallery');
        } elseif ($request->filled('image')) {
            $validated['image'] = $request->image;
        }

        GalleryItem::create($validated);

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery photo added successfully.');
    }

    public function edit(GalleryItem $gallery): View
    {
        $hotels = Hotel::all();
        $item = $gallery;
        $existingCategories = GalleryItem::distinct()->pluck('category')->filter()->values();

        return view('admin.gallery.edit', compact('hotels', 'item', 'existingCategories'));
    }

    public function update(Request $request, GalleryItem $gallery): RedirectResponse
    {
        $validated = $request->validate($this->rules($gallery->id));
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        if ($request->boolean('remove_image')) {
            ImageService::deleteFile($gallery->image);
            $validated['image'] = null;
        } elseif ($request->hasFile('image_file')) {
            ImageService::deleteFile($gallery->image);
            $validated['image'] = ImageService::uploadAsWebp($request->file('image_file'), 'gallery');
        } elseif ($request->filled('image')) {
            $validated['image'] = $request->image;
        }

        $gallery->update($validated);

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery photo updated successfully.');
    }

    public function toggleStatus(GalleryItem $gallery): RedirectResponse
    {
        $gallery->update(['is_active' => !$gallery->is_active]);
        return back()->with('success', 'Gallery item status updated.');
    }

    public function destroy(GalleryItem $gallery): RedirectResponse
    {
        ImageService::deleteFile($gallery->image);
        $gallery->delete();

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery photo deleted successfully.');
    }

    private function rules(?int $id = null): array
    {
        return [
            'hotel_id' => ['nullable', 'exists:hotels,id'],
            'title' => ['required', 'string', 'max:150'],
            'category' => ['required', 'string', 'max:100'],
            'image' => ['nullable', 'string', 'max:500'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,avif,gif', 'max:8192'],
            'remove_image' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
