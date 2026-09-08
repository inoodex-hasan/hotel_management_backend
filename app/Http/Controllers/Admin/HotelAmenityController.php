<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelAmenity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HotelAmenityController extends Controller
{
    public function index(Hotel $hotel): View
    {
        $amenities = $hotel->amenities()->latest()->get();
        return view('admin.hotels.amenities.index', compact('hotel', 'amenities'));
    }

    public function create(Hotel $hotel): View
    {
        return view('admin.hotels.amenities.create', compact('hotel'));
    }

    public function store(Request $request, Hotel $hotel): RedirectResponse
    {
        if ($request->has('bulk_amenities') && is_array($request->input('bulk_amenities'))) {
            $amenitiesData = $request->input('bulk_amenities');
            $addedCount = 0;

            foreach ($amenitiesData as $amenity) {
                if (!empty($amenity['name'])) {
                    $hotel->amenities()->create([
                        'amenity_name' => substr($amenity['name'], 0, 100),
                        'icon' => substr($amenity['icon'] ?? '', 0, 50),
                        'description' => null,
                        'is_featured' => false,
                    ]);
                    $addedCount++;
                }
            }

            return redirect()->route('admin.hotels.amenities.index', $hotel)
                ->with('success', $addedCount . ' amenities successfully added.');
        }

        $validated = $request->validate([
            'amenity_name' => ['required', 'string', 'max:100'],
            'icon' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');

        $hotel->amenities()->create($validated);

        return redirect()->route('admin.hotels.amenities.index', $hotel)->with('success', 'Amenity added.');
    }

    public function edit(Hotel $hotel, HotelAmenity $amenity): View
    {
        return view('admin.hotels.amenities.edit', compact('hotel', 'amenity'));
    }

    public function update(Request $request, Hotel $hotel, HotelAmenity $amenity): RedirectResponse
    {
        $validated = $request->validate([
            'amenity_name' => ['required', 'string', 'max:100'],
            'icon' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');

        $amenity->update($validated);

        return redirect()->route('admin.hotels.amenities.index', $hotel)->with('success', 'Amenity updated.');
    }

    public function destroy(Hotel $hotel, HotelAmenity $amenity): RedirectResponse
    {
        $amenity->delete();
        return redirect()->route('admin.hotels.amenities.index', $hotel)->with('success', 'Amenity removed.');
    }
}
