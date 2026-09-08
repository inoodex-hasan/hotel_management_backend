<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Hotel;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FacilityController extends Controller
{
    public function index(Request $request): View
    {
        $facilities = Facility::query()
            ->with('hotel')
            ->orderBy('sort_order')
            ->orderBy('id', 'asc')
            ->paginate(15);

        return view('admin.facilities.index', compact('facilities'));
    }

    public function create(): View
    {
        $hotels = Hotel::all();
        $facility = new Facility([
            'is_active' => true,
            'sort_order' => 0,
            'icon' => 'Sparkles',
            'opening_hours' => '6:00 AM - 10:00 PM',
        ]);

        return view('admin.facilities.create', compact('hotels', 'facility'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        // Default to The Azura hotel if not specified
        if (empty($validated['hotel_id'])) {
            $defaultHotel = Hotel::first();
            $validated['hotel_id'] = $defaultHotel?->id;
        }

        // Handle Image upload (convert to WebP)
        if ($request->hasFile('image_file')) {
            $validated['image'] = ImageService::uploadAsWebp($request->file('image_file'), 'facilities');
        } elseif ($request->filled('image')) {
            $validated['image'] = $request->image;
        }

        // Parse features text lines to array
        if ($request->filled('features_text')) {
            $validated['features'] = array_values(array_filter(array_map('trim', explode("\n", (string) $request->features_text))));
        } else {
            $validated['features'] = [];
        }

        Facility::create($validated);

        return redirect()->route('admin.facilities.index')->with('success', 'Facility created successfully.');
    }

    public function edit(Facility $facility): View
    {
        $hotels = Hotel::all();
        return view('admin.facilities.edit', compact('hotels', 'facility'));
    }

    public function update(Request $request, Facility $facility): RedirectResponse
    {
        $validated = $request->validate($this->rules($facility->id));
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        // Handle Image removal or replacement
        if ($request->boolean('remove_image')) {
            ImageService::deleteFile($facility->image);
            $validated['image'] = null;
        } elseif ($request->hasFile('image_file')) {
            ImageService::deleteFile($facility->image);
            $validated['image'] = ImageService::uploadAsWebp($request->file('image_file'), 'facilities');
        } elseif ($request->filled('image')) {
            $validated['image'] = $request->image;
        }

        // Parse features text lines to array
        if ($request->has('features_text')) {
            $validated['features'] = array_values(array_filter(array_map('trim', explode("\n", (string) $request->features_text))));
        }

        $facility->update($validated);

        return redirect()->route('admin.facilities.index')->with('success', 'Facility updated successfully.');
    }

    public function toggleStatus(Facility $facility): RedirectResponse
    {
        $facility->update(['is_active' => !$facility->is_active]);
        return back()->with('success', 'Facility status updated.');
    }

    public function destroy(Facility $facility): RedirectResponse
    {
        ImageService::deleteFile($facility->image);
        $facility->delete();

        return redirect()->route('admin.facilities.index')->with('success', 'Facility deleted successfully.');
    }

    private function rules(?int $id = null): array
    {
        return [
            'hotel_id' => ['nullable', 'exists:hotels,id'],
            'title' => ['required', 'string', 'max:150'],
            'subtitle' => ['nullable', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:50'],
            'image' => ['nullable', 'string', 'max:500'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,avif,gif', 'max:8192'],
            'remove_image' => ['nullable', 'boolean'],
            'opening_hours' => ['nullable', 'string', 'max:100'],
            'features_text' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
