<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Hotel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HotelController extends Controller
{
    public function index(Request $request): View
    {
        $query = Hotel::with('city.country')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        if ($request->filled('search')) {
            $s = '%'.$request->search.'%';
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', $s)
                    ->orWhere('address', 'like', $s);
            });
        }

        $hotels = $query->paginate(15)->withQueryString();
        $cities = City::with('country')->orderBy('name')->get();

        return view('admin.hotels.index', compact('hotels', 'cities'));
    }

    public function create(): View
    {
        $cities = City::with('country')->orderBy('name')->get();

        return view('admin.hotels.create', compact('cities'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $validated['is_international'] = $request->boolean('is_international');

        Hotel::create($validated);

        clear_api_cache();

        return redirect()->route('admin.hotels.index')->with('success', 'Hotel created.');
    }

    public function show(Hotel $hotel): View
    {
        $hotel->load(['city.country', 'amenities', 'media', 'roomTypes']);

        return view('admin.hotels.show', compact('hotel'));
    }

    public function edit(Hotel $hotel): View
    {
        $cities = City::with('country')->orderBy('name')->get();

        return view('admin.hotels.edit', compact('hotel', 'cities'));
    }

    public function update(Request $request, Hotel $hotel): RedirectResponse
    {
        $validated = $request->validate($this->rules($hotel));
        $validated['is_international'] = $request->boolean('is_international');

        $hotel->update($validated);

        clear_api_cache();

        return redirect()->route('admin.hotels.index')->with('success', 'Hotel updated.');
    }

    public function destroy(Hotel $hotel): RedirectResponse
    {
        $hotel->delete();

        clear_api_cache();

        return redirect()->route('admin.hotels.index')->with('success', 'Hotel removed.');
    }

    /**
     * @return array<string, mixed>
     */
    private function rules(Hotel $hotel = null): array
    {
        return [
            'city_id' => ['required', 'exists:cities,id'],
            'name' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:hotels,slug,' . ($hotel->id ?? '')],
            'description' => ['nullable', 'string'],
            'star_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'address' => ['required', 'string'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'contact_phone' => ['nullable', 'string', 'max:20'],
            'contact_email' => ['nullable', 'email', 'max:150'],
            'check_in_time' => ['nullable', 'date_format:H:i'],
            'check_out_time' => ['nullable', 'date_format:H:i'],
            'status' => ['required', 'in:active,inactive'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string'],
        ];
    }
}
