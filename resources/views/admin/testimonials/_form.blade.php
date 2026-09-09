@csrf

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- LEFT 2 COLS: Guest Details & Review -->
    <div class="lg:col-span-2 space-y-6">
        <div class="panel">
            <h5 class="text-md font-bold uppercase tracking-wider text-slate-700 dark:text-white mb-5 pb-3 border-b border-slate-100 dark:border-slate-800">
                Guest Review Details
            </h5>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Guest Name -->
                <div>
                    <label for="guest_name" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">
                        Guest Name <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="guest_name" id="guest_name" value="{{ old('guest_name', $testimonial->guest_name) }}"
                        placeholder="e.g. Daniel Morgan, Emma Wilson"
                        class="form-input text-sm @error('guest_name') border-danger @enderror" required />
                    @error('guest_name')
                        <span class="text-danger text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Rating -->
                <div>
                    <label for="rating" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">
                        Star Rating <span class="text-danger">*</span>
                    </label>
                    <select name="rating" id="rating" class="form-select text-sm @error('rating') border-danger @enderror" required>
                        <option value="5" {{ old('rating', $testimonial->rating ?? 5) == 5 ? 'selected' : '' }}>⭐⭐⭐⭐⭐ 5.0 - Exceptional</option>
                        <option value="4" {{ old('rating', $testimonial->rating) == 4 ? 'selected' : '' }}>⭐⭐⭐⭐ 4.0 - Excellent</option>
                        <option value="3" {{ old('rating', $testimonial->rating) == 3 ? 'selected' : '' }}>⭐⭐⭐ 3.0 - Good</option>
                        <option value="2" {{ old('rating', $testimonial->rating) == 2 ? 'selected' : '' }}>⭐⭐ 2.0 - Fair</option>
                        <option value="1" {{ old('rating', $testimonial->rating) == 1 ? 'selected' : '' }}>⭐ 1.0 - Poor</option>
                    </select>
                    @error('rating')
                        <span class="text-danger text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Stay Room Type -->
                <div>
                    <label for="stay_room" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">
                        Room / Suite Stayed In
                    </label>
                    <input type="text" name="stay_room" id="stay_room" value="{{ old('stay_room', $testimonial->stay_room) }}"
                        placeholder="e.g. Executive Room, Deluxe Suite, Penthouse"
                        class="form-input text-sm @error('stay_room') border-danger @enderror" />
                    @error('stay_room')
                        <span class="text-danger text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">
                        Guest City & Country
                    </label>
                    <input type="text" name="location" id="location" value="{{ old('location', $testimonial->location) }}"
                        placeholder="e.g. New York, United States"
                        class="form-input text-sm @error('location') border-danger @enderror" />
                    @error('location')
                        <span class="text-danger text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Hotel Selection -->
                <div class="md:col-span-2">
                    <label for="hotel_id" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">
                        Hotel Property
                    </label>
                    <select name="hotel_id" id="hotel_id" class="form-select text-sm @error('hotel_id') border-danger @enderror">
                        @foreach($hotels as $hotel)
                            <option value="{{ $hotel->id }}" {{ old('hotel_id', $testimonial->hotel_id) == $hotel->id ? 'selected' : '' }}>
                                {{ $hotel->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('hotel_id')
                        <span class="text-danger text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Review Quote -->
                <div class="md:col-span-2">
                    <label for="quote" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">
                        Review Quote / Testimonial Text <span class="text-danger">*</span>
                    </label>
                    <textarea name="quote" id="quote" rows="4"
                        placeholder="Write the guest feedback or review quote..."
                        class="form-input text-sm @error('quote') border-danger @enderror" required>{{ old('quote', $testimonial->quote) }}</textarea>
                    @error('quote')
                        <span class="text-danger text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT 1 COL: Avatar & Status -->
    <div class="space-y-6">
        <!-- Guest Photo Panel -->
        <div class="panel">
            <h5 class="text-md font-bold uppercase tracking-wider text-slate-700 dark:text-white mb-4 pb-2 border-b border-slate-100 dark:border-slate-800">
                Guest Avatar / Photo
            </h5>

            @php
                $currentAvatar = $testimonial->avatar;
                if ($currentAvatar && (str_starts_with($currentAvatar, '/storage/') || str_starts_with($currentAvatar, 'storage/'))) {
                    $currentAvatar = url(ltrim($currentAvatar, '/'));
                }
            @endphp

            @if($currentAvatar)
                <div class="mb-4">
                    <span class="text-xs font-bold uppercase text-slate-500 block mb-2">Current Photo:</span>
                    <div class="relative w-24 h-24 rounded-full overflow-hidden border-2 border-primary mx-auto shadow-md">
                        <img src="{{ $currentAvatar }}" alt="{{ $testimonial->guest_name }}" class="w-full h-full object-cover" />
                    </div>
                    <div class="mt-3 text-center">
                        <label class="inline-flex items-center text-xs text-danger font-semibold cursor-pointer">
                            <input type="checkbox" name="remove_avatar" value="1" class="form-checkbox text-danger rounded mr-1.5" />
                            Remove photo
                        </label>
                    </div>
                </div>
            @endif

            <div class="space-y-3">
                <div>
                    <label for="avatar_file" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">
                        Upload Photo (WebP auto-optimized)
                    </label>
                    <input type="file" name="avatar_file" id="avatar_file" accept="image/*"
                        class="form-input file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 text-xs" />
                    <span class="text-[11px] text-slate-400 mt-1 block">Square photo recommended, up to 4MB</span>
                    @error('avatar_file')
                        <span class="text-danger text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="relative flex py-1 items-center">
                    <div class="flex-grow border-t border-slate-200 dark:border-slate-700"></div>
                    <span class="flex-shrink mx-2 text-[10px] text-slate-400 uppercase font-semibold">or direct URL</span>
                    <div class="flex-grow border-t border-slate-200 dark:border-slate-700"></div>
                </div>

                <div>
                    <label for="avatar" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">
                        Avatar Image URL
                    </label>
                    <input type="text" name="avatar" id="avatar" value="{{ old('avatar', $testimonial->avatar) }}"
                        placeholder="/images/avatar.jpg or https://..."
                        class="form-input text-xs @error('avatar') border-danger @enderror" />
                    @error('avatar')
                        <span class="text-danger text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Visibility & Status -->
        <div class="panel">
            <h5 class="text-md font-bold uppercase tracking-wider text-slate-700 dark:text-white mb-4 pb-2 border-b border-slate-100 dark:border-slate-800">
                Publish Status
            </h5>

            <div class="space-y-3 mb-5">
                <label class="relative flex items-center cursor-pointer">
                    <input type="checkbox" name="is_approved" value="1" class="form-checkbox text-success rounded h-5 w-5"
                        {{ old('is_approved', $testimonial->is_approved ?? true) ? 'checked' : '' }} />
                    <span class="ml-2.5 text-sm font-semibold text-slate-700 dark:text-slate-200">
                        Approved & Published
                    </span>
                </label>

                <label class="relative flex items-center cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" class="form-checkbox text-primary rounded h-5 w-5"
                        {{ old('is_featured', $testimonial->is_featured ?? true) ? 'checked' : '' }} />
                    <span class="ml-2.5 text-sm font-semibold text-slate-700 dark:text-slate-200">
                        Feature on Homepage Slider
                    </span>
                </label>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="submit" class="btn btn-primary flex-1">
                    {{ $testimonial->exists ? 'Update Testimonial' : 'Save Testimonial' }}
                </button>
                <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary">
                    Cancel
                </a>
            </div>
        </div>
    </div>
</div>
