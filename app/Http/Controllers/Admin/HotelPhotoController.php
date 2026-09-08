<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HotelPhotoController extends Controller
{
    public function index(Hotel $hotel): View
    {
        $photos = $hotel->getMedia('photos');
        return view('admin.hotels.photos.index', compact('hotel', 'photos'));
    }

    public function create(Hotel $hotel): View
    {
        return view('admin.hotels.photos.create', compact('hotel'));
    }

    public function store(Request $request, Hotel $hotel): RedirectResponse
    {
        $request->validate([
            'photos' => ['required', 'array'],
            'photos.*' => ['image', 'max:4096'],
            'caption' => ['nullable', 'string', 'max:200'],
            'is_cover' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        if ($request->boolean('is_cover')) {
            foreach ($hotel->getMedia('photos') as $media) {
                if ($media->getCustomProperty('is_cover')) {
                    $media->setCustomProperty('is_cover', false);
                    $media->save();
                }
            }
        }

        $sortOrder = $request->filled('sort_order') ? (int) $request->sort_order : 0;
        $isFirst = true;

        foreach ($request->file('photos') as $photoFile) {
            $media = $hotel->addMedia($photoFile)
                ->withCustomProperties([
                    'caption' => $request->caption,
                    'is_cover' => $request->boolean('is_cover') && $isFirst,
                ])
                ->toMediaCollection('photos');

            $media->order_column = $sortOrder++;
            $media->save();
            
            $isFirst = false;
        }

        return redirect()->route('admin.hotels.photos.index', $hotel)->with('success', 'Photos uploaded successfully.');
    }

    public function edit(Hotel $hotel, Media $photo): View
    {
        return view('admin.hotels.photos.edit', compact('hotel', 'photo'));
    }

    public function update(Request $request, Hotel $hotel, Media $photo): RedirectResponse
    {
        $request->validate([
            'caption' => ['nullable', 'string', 'max:200'],
            'is_cover' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        if ($request->boolean('is_cover') && !$photo->getCustomProperty('is_cover')) {
            foreach ($hotel->getMedia('photos') as $otherMedia) {
                if ($otherMedia->id !== $photo->id && $otherMedia->getCustomProperty('is_cover')) {
                    $otherMedia->setCustomProperty('is_cover', false);
                    $otherMedia->save();
                }
            }
        }

        $photo->setCustomProperty('caption', $request->caption);
        $photo->setCustomProperty('is_cover', $request->boolean('is_cover'));

        if ($request->filled('sort_order')) {
            $photo->order_column = (int) $request->sort_order;
        }

        $photo->save();

        return redirect()->route('admin.hotels.photos.index', $hotel)->with('success', 'Photo updated.');
    }

    public function destroy(Hotel $hotel, Media $photo): RedirectResponse
    {
        $photo->delete();

        return redirect()->route('admin.hotels.photos.index', $hotel)->with('success', 'Photo removed.');
    }
}
