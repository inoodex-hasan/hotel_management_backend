<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use App\Models\Hotel;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HeroSlideController extends Controller
{
    public function index(): View
    {
        $slides = HeroSlide::with('hotel')->orderBy('order', 'asc')->paginate(15);
        return view('admin.hero-slides.index', compact('slides'));
    }

    public function create(): View
    {
        $hotels = Hotel::where('status', 'active')->orderBy('name')->get();
        return view('admin.hero-slides.create', compact('hotels'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'hotel_id' => ['nullable', 'exists:hotels,id'],
            'title' => ['nullable', 'string', 'max:190'],
            'subtitle' => ['nullable', 'string', 'max:190'],
            'description' => ['nullable', 'string'],
            'badge_text' => ['nullable', 'string', 'max:100'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_link' => ['nullable', 'string', 'max:255'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,avif', 'max:5120'],
            'image_url' => ['nullable', 'string', 'max:500'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $imageUrl = $request->input('image_url');
        if ($request->hasFile('image_file')) {
            $imageUrl = ImageService::uploadAsWebp($request->file('image_file'), 'hero-slides', 1920);
        }

        if (empty($imageUrl)) {
            return back()->withInput()->with('error', 'Please provide an image file or an image URL.');
        }

        $validated['image_url'] = $imageUrl;
        $validated['order'] = $request->input('order', 0) ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        HeroSlide::create($validated);

        return redirect()->route('admin.hero-slides.index')->with('success', 'Hero slide created successfully.');
    }

    public function edit(HeroSlide $heroSlide): View
    {
        $hotels = Hotel::where('status', 'active')->orderBy('name')->get();
        return view('admin.hero-slides.edit', compact('heroSlide', 'hotels'));
    }

    public function update(Request $request, HeroSlide $heroSlide): RedirectResponse
    {
        $validated = $request->validate([
            'hotel_id' => ['nullable', 'exists:hotels,id'],
            'title' => ['nullable', 'string', 'max:190'],
            'subtitle' => ['nullable', 'string', 'max:190'],
            'description' => ['nullable', 'string'],
            'badge_text' => ['nullable', 'string', 'max:100'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_link' => ['nullable', 'string', 'max:255'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,avif', 'max:5120'],
            'image_url' => ['nullable', 'string', 'max:500'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image_file')) {
            $validated['image_url'] = ImageService::uploadAsWebp($request->file('image_file'), 'hero-slides', 1920);
        } elseif ($request->filled('image_url')) {
            $validated['image_url'] = $request->input('image_url');
        }

        $validated['order'] = $request->input('order', 0) ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        $heroSlide->update($validated);

        return redirect()->route('admin.hero-slides.index')->with('success', 'Hero slide updated successfully.');
    }

    public function destroy(HeroSlide $heroSlide): RedirectResponse
    {
        $heroSlide->delete();
        return redirect()->route('admin.hero-slides.index')->with('success', 'Hero slide deleted successfully.');
    }
}
