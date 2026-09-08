<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dining;
use App\Models\Hotel;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DiningController extends Controller
{
    public function index(Request $request): View
    {
        $venues = Dining::query()
            ->with('hotel')
            ->orderBy('sort_order')
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('admin.dining.index', compact('venues'));
    }

    public function create(): View
    {
        $hotels = Hotel::all();
        $dining = new Dining([
            'is_active' => true,
            'sort_order' => 0,
            'hours' => '7:00 AM - 10:00 PM',
            'phone' => '+880 1401 777 888',
            'location' => 'Lobby Level',
            'serves' => 'Breakfast, Lunch, Dinner',
        ]);

        return view('admin.dining.create', compact('hotels', 'dining'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        $validated['slug'] = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);

        // Default to The Azura hotel if not specified
        if (empty($validated['hotel_id'])) {
            $defaultHotel = Hotel::first();
            $validated['hotel_id'] = $defaultHotel?->id;
        }

        // Handle Image upload (convert to WebP)
        if ($request->hasFile('image_file')) {
            $validated['image'] = ImageService::uploadAsWebp($request->file('image_file'), 'dining');
        } elseif ($request->filled('image')) {
            $validated['image'] = $request->image;
        }

        // Parse features text lines to array
        if ($request->filled('features_text')) {
            $validated['features'] = array_values(array_filter(array_map('trim', explode("\n", (string) $request->features_text))));
        } else {
            $validated['features'] = [];
        }

        Dining::create($validated);

        return redirect()->route('admin.dining.index')->with('success', 'Dining venue created successfully.');
    }

    public function edit(Dining $dining): View
    {
        $hotels = Hotel::all();
        return view('admin.dining.edit', compact('hotels', 'dining'));
    }

    public function update(Request $request, Dining $dining): RedirectResponse
    {
        $validated = $request->validate($this->rules($dining->id));
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        $validated['slug'] = !empty($validated['slug']) ? Str::slug($validated['slug']) : ($dining->slug ?: Str::slug($validated['name']));

        // Handle Image removal or replacement
        if ($request->boolean('remove_image')) {
            ImageService::deleteFile($dining->image);
            $validated['image'] = null;
        } elseif ($request->hasFile('image_file')) {
            ImageService::deleteFile($dining->image);
            $validated['image'] = ImageService::uploadAsWebp($request->file('image_file'), 'dining');
        } elseif ($request->filled('image')) {
            $validated['image'] = $request->image;
        }

        // Parse features text lines to array
        if ($request->has('features_text')) {
            $validated['features'] = array_values(array_filter(array_map('trim', explode("\n", (string) $request->features_text))));
        }

        $dining->update($validated);

        return redirect()->route('admin.dining.index')->with('success', 'Dining venue updated successfully.');
    }

    public function toggleStatus(Dining $dining): RedirectResponse
    {
        $dining->update(['is_active' => !$dining->is_active]);
        return back()->with('success', 'Dining venue status updated.');
    }

    public function destroy(Dining $dining): RedirectResponse
    {
        ImageService::deleteFile($dining->image);
        $dining->delete();

        return redirect()->route('admin.dining.index')->with('success', 'Dining venue deleted successfully.');
    }

    private function rules(?int $id = null): array
    {
        return [
            'hotel_id' => ['nullable', 'exists:hotels,id'],
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:150', 'unique:dining,slug,' . $id],
            'label' => ['nullable', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:500'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,avif,gif', 'max:8192'],
            'remove_image' => ['nullable', 'boolean'],
            'location' => ['nullable', 'string', 'max:150'],
            'serves' => ['nullable', 'string', 'max:200'],
            'phone' => ['nullable', 'string', 'max:50'],
            'hours' => ['nullable', 'string', 'max:100'],
            'features_text' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
