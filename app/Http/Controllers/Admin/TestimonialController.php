<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Testimonial;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(Request $request): View
    {
        $query = Testimonial::query()->with('hotel');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('guest_name', 'like', '%' . $request->search . '%')
                    ->orWhere('location', 'like', '%' . $request->search . '%')
                    ->orWhere('stay_room', 'like', '%' . $request->search . '%')
                    ->orWhere('quote', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->filled('hotel_id')) {
            $query->where('hotel_id', $request->hotel_id);
        }

        $testimonials = $query->latest()
            ->paginate(15)
            ->withQueryString();

        $hotels = Hotel::all();

        return view('admin.testimonials.index', compact('testimonials', 'hotels'));
    }

    public function create(): View
    {
        $hotels = Hotel::all();
        $testimonial = new Testimonial([
            'rating' => 5,
            'is_featured' => true,
            'is_approved' => true,
        ]);

        return view('admin.testimonials.create', compact('hotels', 'testimonial'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_approved'] = $request->boolean('is_approved');
        $validated['rating'] = (int) ($validated['rating'] ?? 5);

        if (empty($validated['hotel_id'])) {
            $defaultHotel = Hotel::first();
            $validated['hotel_id'] = $defaultHotel?->id;
        }

        if ($request->hasFile('avatar_file')) {
            $validated['avatar'] = ImageService::uploadAsWebp($request->file('avatar_file'), 'testimonials');
        } elseif ($request->filled('avatar')) {
            $validated['avatar'] = $request->avatar;
        }

        Testimonial::create($validated);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial created successfully.');
    }

    public function edit(Testimonial $testimonial): View
    {
        $hotels = Hotel::all();
        return view('admin.testimonials.edit', compact('hotels', 'testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $validated = $request->validate($this->rules($testimonial->id));
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_approved'] = $request->boolean('is_approved');
        $validated['rating'] = (int) ($validated['rating'] ?? 5);

        if ($request->boolean('remove_avatar')) {
            ImageService::deleteFile($testimonial->avatar);
            $validated['avatar'] = null;
        } elseif ($request->hasFile('avatar_file')) {
            ImageService::deleteFile($testimonial->avatar);
            $validated['avatar'] = ImageService::uploadAsWebp($request->file('avatar_file'), 'testimonials');
        } elseif ($request->filled('avatar')) {
            $validated['avatar'] = $request->avatar;
        }

        $testimonial->update($validated);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated successfully.');
    }

    public function toggleApproval(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update(['is_approved' => !$testimonial->is_approved]);
        return back()->with('success', 'Testimonial status updated.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        ImageService::deleteFile($testimonial->avatar);
        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial deleted successfully.');
    }

    private function rules(?int $id = null): array
    {
        return [
            'hotel_id' => ['nullable', 'exists:hotels,id'],
            'guest_name' => ['required', 'string', 'max:150'],
            'location' => ['nullable', 'string', 'max:150'],
            'stay_room' => ['nullable', 'string', 'max:150'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'quote' => ['required', 'string'],
            'avatar' => ['nullable', 'string', 'max:500'],
            'avatar_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,avif,gif', 'max:4096'],
            'remove_avatar' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'is_approved' => ['nullable', 'boolean'],
        ];
    }
}
