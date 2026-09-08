<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CountryController extends Controller
{
    public function index(Request $request): View
    {
        $query = Country::query()->latest();

        if ($request->filled('search')) {
            $s = '%'.$request->search.'%';
            $query->where('name', 'like', $s)
                ->orWhere('iso_code', 'like', $s);
        }

        $countries = $query->paginate(15)->withQueryString();

        return view('admin.countries.index', compact('countries'));
    }

    public function create(): View
    {
        return view('admin.countries.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'iso_code' => ['required', 'string', 'max:3', 'unique:countries,iso_code'],
            'phone_code' => ['nullable', 'string', 'max:10'],
        ]);

        Country::create($validated);

        return redirect()->route('admin.countries.index')->with('success', 'Country created.');
    }

    public function edit(Country $country): View
    {
        return view('admin.countries.edit', compact('country'));
    }

    public function update(Request $request, Country $country): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'iso_code' => ['required', 'string', 'max:3', 'unique:countries,iso_code,'.$country->id],
            'phone_code' => ['nullable', 'string', 'max:10'],
        ]);

        $country->update($validated);

        return redirect()->route('admin.countries.index')->with('success', 'Country updated.');
    }

    public function destroy(Country $country): RedirectResponse
    {
        if ($country->cities()->exists()) {
            return redirect()->route('admin.countries.index')->with('error', 'Cannot delete country with associated cities.');
        }

        $country->delete();

        return redirect()->route('admin.countries.index')->with('success', 'Country removed.');
    }
}
