<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelPolicy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HotelPolicyController extends Controller
{
    public function index(Hotel $hotel): View
    {
        $policies = $hotel->policies()->latest()->get();
        return view('admin.hotels.policies.index', compact('hotel', 'policies'));
    }

    public function show(Hotel $hotel, HotelPolicy $policy): View
    {
        $categories = $this->categories();
        return view('admin.hotels.policies.show', compact('hotel', 'policy', 'categories'));
    }

    public function create(Hotel $hotel): View
    {
        $categories = $this->categories();
        return view('admin.hotels.policies.create', compact('hotel', 'categories'));
    }

    public function store(Request $request, Hotel $hotel): RedirectResponse
    {
        $validated = $request->validate([
            'category' => ['required', 'in:child,pet,payment,check_in_out,general'],
            'title' => ['required', 'string', 'max:200'],
            'description' => ['required', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $hotel->policies()->create($validated);

        return redirect()->route('admin.hotels.policies.index', $hotel)->with('success', 'Policy added.');
    }

    public function edit(Hotel $hotel, HotelPolicy $policy): View
    {
        $categories = $this->categories();
        return view('admin.hotels.policies.edit', compact('hotel', 'policy', 'categories'));
    }

    public function update(Request $request, Hotel $hotel, HotelPolicy $policy): RedirectResponse
    {
        $validated = $request->validate([
            'category' => ['required', 'in:child,pet,payment,check_in_out,general'],
            'title' => ['required', 'string', 'max:200'],
            'description' => ['required', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $policy->update($validated);

        return redirect()->route('admin.hotels.policies.index', $hotel)->with('success', 'Policy updated.');
    }

    public function destroy(Hotel $hotel, HotelPolicy $policy): RedirectResponse
    {
        $policy->delete();
        return redirect()->route('admin.hotels.policies.index', $hotel)->with('success', 'Policy removed.');
    }

    private function categories(): array
    {
        return [
            'child' => 'Child Policy',
            'pet' => 'Pet Policy',
            'payment' => 'Payment Policy',
            'check_in_out' => 'Check-in/Out Policy',
            'general' => 'General Policy',
        ];
    }
}
