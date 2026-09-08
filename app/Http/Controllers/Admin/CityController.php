<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CityController extends Controller
{
    public function index(Request $request): View
    {
        $query = City::with('country')->latest();

        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        if ($request->filled('search')) {
            $s = '%'.$request->search.'%';
            $query->where('name', 'like', $s)
                ->orWhere('iata_code', 'like', $s);
        }

        $cities = $query->paginate(15)->withQueryString();
        $countries = Country::orderBy('name')->get();

        return view('admin.cities.index', compact('cities', 'countries'));
    }

    public function create(): View
    {
        $countries = Country::orderBy('name')->get();

        return view('admin.cities.create', compact('countries'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'country_id' => ['required', 'exists:countries,id'],
            'name' => ['required', 'string', 'max:100'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'iata_code' => ['nullable', 'string', 'max:5', 'unique:cities,iata_code'],
        ]);

        $city = City::create($validated);

        if ($request->hasFile('photo')) {
            $city->addMediaFromRequest('photo')->toMediaCollection('photo');
        }

        return redirect()->route('admin.cities.index')->with('success', 'City created.');
    }

    public function edit(City $city): View
    {
        $countries = Country::orderBy('name')->get();

        return view('admin.cities.edit', compact('city', 'countries'));
    }

    public function update(Request $request, City $city): RedirectResponse
    {
        $validated = $request->validate([
            'country_id' => ['required', 'exists:countries,id'],
            'name' => ['required', 'string', 'max:100'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'iata_code' => ['nullable', 'string', 'max:5', 'unique:cities,iata_code,'.$city->id],
        ]);

        $city->update($validated);

        if ($request->hasFile('photo')) {
            $city->clearMediaCollection('photo');
            $city->addMediaFromRequest('photo')->toMediaCollection('photo');
        }

        return redirect()->route('admin.cities.index')->with('success', 'City updated.');
    }

    public function destroy(City $city): RedirectResponse
    {
        if ($city->hotels()->exists()) {
            return redirect()->route('admin.cities.index')->with('error', 'Cannot delete city with associated hotels.');
        }

        $city->delete();

        return redirect()->route('admin.cities.index')->with('success', 'City removed.');
    }
}