@php
    $d = $dining ?? null;
    $featureLines = is_array($d?->features) ? implode("\n", $d->features) : ($d?->features ?? '');
    $imgSrc = $d?->image;
    if ($imgSrc && (str_starts_with($imgSrc, '/storage/') || str_starts_with($imgSrc, 'storage/'))) {
        $imgSrc = url(ltrim($imgSrc, '/'));
    }
@endphp

<div class="grid grid-cols-1 gap-5 md:grid-cols-2">
    <!-- Venue Name -->
    <div class="form-group">
        <label for="name" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
            Venue Name <span class="text-danger">*</span>
        </label>
        <input type="text" name="name" id="name" class="form-input" required maxlength="150"
            placeholder="e.g. The Signature Restaurant"
            value="{{ old('name', $d?->name) }}" />
        @error('name')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Slug -->
    <div class="form-group">
        <label for="slug" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
            Slug (URL Identifier)
        </label>
        <input type="text" name="slug" id="slug" class="form-input" maxlength="150"
            placeholder="e.g. the-signature-restaurant (auto-generated if empty)"
            value="{{ old('slug', $d?->slug) }}" />
        @error('slug')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Label / Tagline -->
    <div class="form-group">
        <label for="label" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
            Subtitle / Eyebrow Label
        </label>
        <input type="text" name="label" id="label" class="form-input" maxlength="150"
            placeholder="e.g. BON APPÉTIT DURING VACATIONS AND TRIPS"
            value="{{ old('label', $d?->label) }}" />
        @error('label')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Location -->
    <div class="form-group">
        <label for="location" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
            Location in Hotel
        </label>
        <input type="text" name="location" id="location" class="form-input" maxlength="150"
            placeholder="e.g. Lobby Level / 15th Floor Rooftop"
            value="{{ old('location', $d?->location) }}" />
        @error('location')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Serves / Cuisine Type -->
    <div class="form-group">
        <label for="serves" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
            Cuisine / Meals Served
        </label>
        <input type="text" name="serves" id="serves" class="form-input" maxlength="200"
            placeholder="e.g. Breakfast, Brunch, Lunch, Dinner, Seafood"
            value="{{ old('serves', $d?->serves) }}" />
        @error('serves')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Opening Hours -->
    <div class="form-group">
        <label for="hours" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
            Opening Hours
        </label>
        <input type="text" name="hours" id="hours" class="form-input" maxlength="100"
            placeholder="e.g. 7:00 AM - 10:00 PM"
            value="{{ old('hours', $d?->hours) }}" />
        @error('hours')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Contact Phone -->
    <div class="form-group">
        <label for="phone" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
            Reservation Phone
        </label>
        <input type="text" name="phone" id="phone" class="form-input" maxlength="50"
            placeholder="e.g. +880 1401 777 888"
            value="{{ old('phone', $d?->phone) }}" />
        @error('phone')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Sort Order -->
    <div class="form-group">
        <label for="sort_order" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
            Sort Order
        </label>
        <input type="number" name="sort_order" id="sort_order" class="form-input" min="0"
            placeholder="0"
            value="{{ old('sort_order', $d?->sort_order ?? 0) }}" />
        @error('sort_order')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Image Upload with Preview -->
    <div class="form-group md:col-span-2 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/30 p-5 space-y-4">
        <div>
            <h4 class="text-sm font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                <span>📸</span> Venue Main Photo
            </h4>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Upload a high-resolution photo for this dining venue (auto-converted to WebP).</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
            <div class="md:col-span-2 space-y-3">
                <div>
                    <label for="image_file" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                        Upload Image File <span class="text-xs font-normal text-slate-400">(Recommended: 1920×1080)</span>
                    </label>
                    <input type="file" name="image_file" id="image_file"
                        class="form-input file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-primary file:text-white hover:file:bg-primary/90 cursor-pointer"
                        accept="image/*" />
                    @error('image_file')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="image" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                        Or External / Static Image Path
                    </label>
                    <input type="text" name="image" id="image" class="form-input text-xs font-mono"
                        placeholder="e.g. /images/dining/restaurant.avif"
                        value="{{ old('image', $d?->image) }}" />
                    @error('image')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            @if($imgSrc)
                <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#1b2e4b] p-3 shadow-sm flex flex-col items-center text-center">
                    <div class="relative w-full aspect-[4/3] rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800 mb-2 border border-slate-200 dark:border-slate-700">
                        <img src="{{ $imgSrc }}" alt="{{ $d->name }}" class="w-full h-full object-cover" onerror="this.src='/favicon.ico'">
                    </div>
                    <span class="text-[11px] font-mono text-slate-500 dark:text-slate-400 truncate w-full mb-2" title="{{ $d->image }}">{{ basename($d->image) }}</span>
                    <label class="inline-flex items-center gap-1.5 text-xs text-danger font-semibold cursor-pointer hover:underline">
                        <input type="checkbox" name="remove_image" value="1" class="form-checkbox text-danger rounded">
                        <span>Remove this photo</span>
                    </label>
                </div>
            @endif
        </div>
    </div>

    <!-- Description -->
    <div class="form-group md:col-span-2">
        <label for="description" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
            Detailed Description & Culinary Story
        </label>
        <textarea name="description" id="description" rows="4" class="form-textarea"
            placeholder="Describe the atmosphere, signature dishes, chef specialities, and dining experience...">{{ old('description', $d?->description) }}</textarea>
        @error('description')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Features (one per line) -->
    <div class="form-group md:col-span-2">
        <label for="features_text" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
            Key Features & Highlights <span class="text-xs font-normal text-slate-400">(One feature per line)</span>
        </label>
        <textarea name="features_text" id="features_text" rows="4" class="form-textarea"
            placeholder="International Menu&#10;Private Dining Room&#10;Wine Collection&#10;Ocean View&#10;Live Music on Weekends">{{ old('features_text', $featureLines) }}</textarea>
        @error('features_text')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Active Status Toggle -->
    <div class="form-group md:col-span-2">
        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
            <input type="checkbox" name="is_active" value="1" class="form-checkbox text-primary rounded"
                {{ old('is_active', $d?->is_active ?? true) ? 'checked' : '' }} />
            <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">Publish this venue on the website</span>
        </label>
    </div>
</div>
