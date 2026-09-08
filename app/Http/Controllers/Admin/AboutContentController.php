<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutContent;
use App\Models\Hotel;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AboutContentController extends Controller
{
    public function edit(Request $request): View
    {
        $hotels = Hotel::where('status', 'active')->orderBy('name')->get();
        $hotelId = $request->input('hotel_id', Hotel::first()?->id);

        $about = AboutContent::firstOrCreate(
            ['hotel_id' => $hotelId],
            [
                'eyebrow' => 'Welcome to',
                'title' => 'The Azura Hotel & Resort',
                'subtitle' => 'A Luxury Beach View Hotel — A Perfect Combination Of Luxuriousness And Affordability.',
                'description' => 'Situated on the picturesque coastline, The Azura Hotel & Resort offers unmatched convenience and accessibility...',
                'feature_1_title' => 'Realistic Summer',
                'feature_1_subtitle' => 'Vacation',
                'feature_2_title' => 'Luxury Standard',
                'feature_2_subtitle' => 'Hotel',
                'main_image_url' => '/images/room1.avif',
                'sub_image_url' => '/images/room2.avif',
                'button_text' => 'Discover More',
                'button_link' => '/about',
                'since_year' => 'Since 2018',
                'story_title' => 'The Trusted Brand of Luxury Hospitality',
                'story_subtitle' => "Enjoy a Luxury Experience in Cox's Bazar",
                'story_description' => "Welcome to one of Cox's Bazar's most renowned landmarks...",
                'contact_phone' => '+880 1401 777 888',
                'contact_email' => 'reservation.theazura@gmail.com',
                'story_image_1' => '/images/about/about-hero.jpg',
                'story_image_2' => '/images/about/about-story.jpg',
                'stat_rooms' => 9,
                'stat_guests' => '15000+',
                'stat_years' => '6+',
                'stat_rating' => '5',
            ]
        );

        return view('admin.about.edit', compact('about', 'hotels', 'hotelId'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'hotel_id' => ['nullable', 'exists:hotels,id'],
            'eyebrow' => ['nullable', 'string', 'max:190'],
            'title' => ['required', 'string', 'max:190'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'feature_1_title' => ['nullable', 'string', 'max:100'],
            'feature_1_subtitle' => ['nullable', 'string', 'max:100'],
            'feature_2_title' => ['nullable', 'string', 'max:100'],
            'feature_2_subtitle' => ['nullable', 'string', 'max:100'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_link' => ['nullable', 'string', 'max:255'],
            'since_year' => ['nullable', 'string', 'max:100'],
            'story_title' => ['nullable', 'string', 'max:190'],
            'story_subtitle' => ['nullable', 'string', 'max:255'],
            'story_description' => ['nullable', 'string'],
            'contact_phone' => ['nullable', 'string', 'max:100'],
            'contact_email' => ['nullable', 'string', 'max:150'],
            'stat_rooms' => ['nullable', 'integer', 'min:0'],
            'stat_guests' => ['nullable', 'string', 'max:50'],
            'stat_years' => ['nullable', 'string', 'max:50'],
            'stat_rating' => ['nullable', 'string', 'max:50'],
            'main_image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,avif', 'max:5120'],
            'sub_image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,avif', 'max:5120'],
            'story_image_1_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,avif', 'max:5120'],
            'story_image_2_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,avif', 'max:5120'],
            'main_image_url' => ['nullable', 'string', 'max:500'],
            'sub_image_url' => ['nullable', 'string', 'max:500'],
            'story_image_1' => ['nullable', 'string', 'max:500'],
            'story_image_2' => ['nullable', 'string', 'max:500'],
        ]);

        $about = AboutContent::firstOrCreate(['hotel_id' => $request->input('hotel_id')]);

        if ($request->hasFile('main_image_file')) {
            $validated['main_image_url'] = ImageService::uploadAsWebp($request->file('main_image_file'), 'about', 1920);
        }

        if ($request->hasFile('sub_image_file')) {
            $validated['sub_image_url'] = ImageService::uploadAsWebp($request->file('sub_image_file'), 'about', 1200);
        }

        if ($request->hasFile('story_image_1_file')) {
            $validated['story_image_1'] = ImageService::uploadAsWebp($request->file('story_image_1_file'), 'about', 1920);
        }

        if ($request->hasFile('story_image_2_file')) {
            $validated['story_image_2'] = ImageService::uploadAsWebp($request->file('story_image_2_file'), 'about', 1200);
        }

        $about->update($validated);

        return redirect()->back()->with('success', 'About content & page details updated successfully.');
    }
}
