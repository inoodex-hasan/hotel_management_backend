@extends('admin.layouts.master')

@section('title', 'About Section & Page Content')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-semibold uppercase">About Content Management</h2>
            <p class="text-xs text-slate-400 mt-1">Manage content and imagery for the Home Page About Section and dedicated About Page</p>
        </div>
        <div class="flex items-center gap-3">
            @if(count($hotels) > 1)
                <form method="GET" action="{{ route('admin.about.edit') }}" class="flex items-center gap-2">
                    <label for="hotel_filter" class="text-xs font-bold uppercase text-slate-500">Hotel:</label>
                    <select id="hotel_filter" name="hotel_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        @foreach($hotels as $h)
                            <option value="{{ $h->id }}" {{ $hotelId == $h->id ? 'selected' : '' }}>{{ $h->name }}</option>
                        @endforeach
                    </select>
                </form>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger mb-6">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.about.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="hotel_id" value="{{ $hotelId }}" />

        <div class="space-y-8">

            <!-- SECTION 1: HOME PAGE ABOUT SECTION -->
            <div class="panel border-t-4 border-primary">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-3 mb-5">
                    <div>
                        <h3 class="text-base font-bold text-slate-800 dark:text-white flex items-center gap-2">
                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-primary text-white text-xs font-bold">1</span>
                            Home Page About Section
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5">Controls the About spotlight section displayed on the main home page</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left: Texts & Features -->
                    <div class="lg:col-span-2 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="eyebrow" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">Eyebrow / Label</label>
                                <input type="text" id="eyebrow" name="eyebrow" class="form-input mt-1" value="{{ old('eyebrow', $about->eyebrow) }}" placeholder="e.g. Welcome to" />
                            </div>
                            <div>
                                <label for="title" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">Section Title <span class="text-danger">*</span></label>
                                <input type="text" id="title" name="title" class="form-input mt-1" value="{{ old('title', $about->title) }}" placeholder="e.g. The Azura Hotel & Resort" required />
                            </div>
                        </div>

                        <div>
                            <label for="subtitle" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">Subtitle / Highlight Tagline</label>
                            <input type="text" id="subtitle" name="subtitle" class="form-input mt-1" value="{{ old('subtitle', $about->subtitle) }}" placeholder="e.g. A Luxury Beach View Hotel..." />
                        </div>

                        <div>
                            <label for="description" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">Detailed Description</label>
                            <textarea id="description" name="description" rows="4" class="form-textarea mt-1" placeholder="Main paragraph description...">{{ old('description', $about->description) }}</textarea>
                        </div>

                        <!-- Feature Badges -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                            <div class="p-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-dark space-y-2">
                                <span class="text-xs font-bold text-primary block uppercase">Feature Badge 1</span>
                                <input type="text" name="feature_1_title" class="form-input text-xs" value="{{ old('feature_1_title', $about->feature_1_title) }}" placeholder="e.g. Realistic Summer" />
                                <input type="text" name="feature_1_subtitle" class="form-input text-xs" value="{{ old('feature_1_subtitle', $about->feature_1_subtitle) }}" placeholder="e.g. Vacation" />
                            </div>
                            <div class="p-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-dark space-y-2">
                                <span class="text-xs font-bold text-primary block uppercase">Feature Badge 2</span>
                                <input type="text" name="feature_2_title" class="form-input text-xs" value="{{ old('feature_2_title', $about->feature_2_title) }}" placeholder="e.g. Luxury Standard" />
                                <input type="text" name="feature_2_subtitle" class="form-input text-xs" value="{{ old('feature_2_subtitle', $about->feature_2_subtitle) }}" placeholder="e.g. Hotel" />
                            </div>
                        </div>

                        <!-- Button Details -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                            <div>
                                <label for="button_text" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">Button Text</label>
                                <input type="text" id="button_text" name="button_text" class="form-input mt-1" value="{{ old('button_text', $about->button_text) }}" placeholder="e.g. Discover More" />
                            </div>
                            <div>
                                <label for="button_link" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">Button Link</label>
                                <input type="text" id="button_link" name="button_link" class="form-input mt-1" value="{{ old('button_link', $about->button_link) }}" placeholder="e.g. /about" />
                            </div>
                        </div>
                    </div>

                    <!-- Right: Section Images -->
                    <div class="space-y-4">
                        <!-- Main Image -->
                        <div class="p-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-dark space-y-2">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-200 block uppercase">Main Showcase Photo</span>
                            <div class="relative w-full h-32 rounded-lg overflow-hidden border border-slate-200 bg-slate-100 shadow-sm">
                                <img id="previewMainImg" src="{{ $about->main_image_full_url }}" alt="Main Preview" class="w-full h-full object-cover" onerror="this.src='https://images.unsplash.com/photo-1590490360182-c33d57733427'" />
                            </div>
                            <input type="file" name="main_image_file" accept="image/*" class="form-input text-xs" onchange="previewImage(event, 'previewMainImg')" />
                            <input type="text" name="main_image_url" class="form-input text-xs" value="{{ old('main_image_url', $about->main_image_url) }}" placeholder="Or path / URL" />
                        </div>

                        <!-- Sub Floating Image -->
                        <div class="p-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-dark space-y-2">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-200 block uppercase">Secondary Floating Photo</span>
                            <div class="relative w-full h-24 rounded-lg overflow-hidden border border-slate-200 bg-slate-100 shadow-sm">
                                <img id="previewSubImg" src="{{ $about->sub_image_full_url }}" alt="Sub Preview" class="w-full h-full object-cover" onerror="this.src='https://images.unsplash.com/photo-1590490360182-c33d57733427'" />
                            </div>
                            <input type="file" name="sub_image_file" accept="image/*" class="form-input text-xs" onchange="previewImage(event, 'previewSubImg')" />
                            <input type="text" name="sub_image_url" class="form-input text-xs" value="{{ old('sub_image_url', $about->sub_image_url) }}" placeholder="Or path / URL" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: ABOUT PAGE STORY & HERO -->
            <div class="panel border-t-4 border-info">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-3 mb-5">
                    <div>
                        <h3 class="text-base font-bold text-slate-800 dark:text-white flex items-center gap-2">
                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-info text-white text-xs font-bold">2</span>
                            Dedicated About Page Story & Contact Points
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5">Controls the hero story header and primary contact bullet points on the /about page</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="since_year" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">Establishment Year / Eyebrow</label>
                                <input type="text" id="since_year" name="since_year" class="form-input mt-1" value="{{ old('since_year', $about->since_year) }}" placeholder="e.g. Since 2018" />
                            </div>
                            <div>
                                <label for="story_title" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">Story Main Title</label>
                                <input type="text" id="story_title" name="story_title" class="form-input mt-1" value="{{ old('story_title', $about->story_title) }}" placeholder="e.g. The Trusted Brand of Luxury Hospitality" />
                            </div>
                        </div>

                        <div>
                            <label for="story_subtitle" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">Story Italic Tagline</label>
                            <input type="text" id="story_subtitle" name="story_subtitle" class="form-input mt-1" value="{{ old('story_subtitle', $about->story_subtitle) }}" placeholder="e.g. Enjoy a Luxury Experience in Cox's Bazar" />
                        </div>

                        <div>
                            <label for="story_description" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">Heritage & Story Narrative</label>
                            <textarea id="story_description" name="story_description" rows="4" class="form-textarea mt-1" placeholder="Narrative story about the hotel...">{{ old('story_description', $about->story_description) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                            <div>
                                <label for="contact_phone" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">Reservation Phone</label>
                                <input type="text" id="contact_phone" name="contact_phone" class="form-input mt-1" value="{{ old('contact_phone', $about->contact_phone) }}" placeholder="+880 1401 777 888" />
                            </div>
                            <div>
                                <label for="contact_email" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">Reservation Email</label>
                                <input type="email" id="contact_email" name="contact_email" class="form-input mt-1" value="{{ old('contact_email', $about->contact_email) }}" placeholder="reservation.theazura@gmail.com" />
                            </div>
                        </div>
                    </div>

                    <!-- Story Images -->
                    <div class="space-y-4">
                        <div class="p-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-dark space-y-2">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-200 block uppercase">Story Hero Photo (Top Right)</span>
                            <div class="relative w-full h-32 rounded-lg overflow-hidden border border-slate-200 bg-slate-100 shadow-sm">
                                <img id="previewStory1" src="{{ $about->story_image_1_full_url }}" alt="Story 1 Preview" class="w-full h-full object-cover" onerror="this.src='https://images.unsplash.com/photo-1590490360182-c33d57733427'" />
                            </div>
                            <input type="file" name="story_image_1_file" accept="image/*" class="form-input text-xs" onchange="previewImage(event, 'previewStory1')" />
                            <input type="text" name="story_image_1" class="form-input text-xs" value="{{ old('story_image_1', $about->story_image_1) }}" placeholder="Or path / URL" />
                        </div>

                        <div class="p-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-dark space-y-2">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-200 block uppercase">Story Experience Photo (Bottom Left)</span>
                            <div class="relative w-full h-24 rounded-lg overflow-hidden border border-slate-200 bg-slate-100 shadow-sm">
                                <img id="previewStory2" src="{{ $about->story_image_2_full_url }}" alt="Story 2 Preview" class="w-full h-full object-cover" onerror="this.src='https://images.unsplash.com/photo-1590490360182-c33d57733427'" />
                            </div>
                            <input type="file" name="story_image_2_file" accept="image/*" class="form-input text-xs" onchange="previewImage(event, 'previewStory2')" />
                            <input type="text" name="story_image_2" class="form-input text-xs" value="{{ old('story_image_2', $about->story_image_2) }}" placeholder="Or path / URL" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: KEY STATISTICS COUNTERS -->
            <div class="panel border-t-4 border-success">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-3 mb-5">
                    <div>
                        <h3 class="text-base font-bold text-slate-800 dark:text-white flex items-center gap-2">
                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-success text-white text-xs font-bold">3</span>
                            Key Milestone Statistics (Animated Counters)
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5">Displayed in the milestone strip on the About Page</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="p-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-dark">
                        <label for="stat_rooms" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">Room Types / Suites</label>
                        <input type="number" id="stat_rooms" name="stat_rooms" class="form-input mt-1 text-center font-bold text-lg" value="{{ old('stat_rooms', $about->stat_rooms) }}" min="0" />
                    </div>
                    <div class="p-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-dark">
                        <label for="stat_guests" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">Happy Guests</label>
                        <input type="text" id="stat_guests" name="stat_guests" class="form-input mt-1 text-center font-bold text-lg" value="{{ old('stat_guests', $about->stat_guests) }}" placeholder="15000+" />
                    </div>
                    <div class="p-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-dark">
                        <label for="stat_years" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">Years of Service</label>
                        <input type="text" id="stat_years" name="stat_years" class="form-input mt-1 text-center font-bold text-lg" value="{{ old('stat_years', $about->stat_years) }}" placeholder="6+" />
                    </div>
                    <div class="p-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-dark">
                        <label for="stat_rating" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">Star Rating / Awards</label>
                        <input type="text" id="stat_rating" name="stat_rating" class="form-input mt-1 text-center font-bold text-lg" value="{{ old('stat_rating', $about->stat_rating) }}" placeholder="5" />
                    </div>
                </div>
            </div>

            <!-- SUBMIT BUTTON BAR -->
            <div class="panel flex items-center justify-between sticky bottom-4 z-20 shadow-xl border border-primary/20 bg-white dark:bg-dark">
                <span class="text-xs text-slate-400">Ensure all images and details are verified before saving.</span>
                <button type="submit" class="btn btn-primary px-8 py-3 text-sm font-bold shadow-lg shadow-primary/20">
                    Save About Content Changes
                </button>
            </div>

        </div>
    </form>

<script>
function previewImage(event, targetId) {
    const input = event.target;
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById(targetId);
            if (preview) {
                preview.src = e.target.result;
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
