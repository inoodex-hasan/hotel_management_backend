@php
    $f = $facility ?? null;
    $featureLines = is_array($f?->features) ? implode("\n", $f->features) : ($f?->features ?? '');
    $imgSrc = $f?->image;
    if ($imgSrc && (str_starts_with($imgSrc, '/storage/') || str_starts_with($imgSrc, 'storage/'))) {
        $imgSrc = url(ltrim($imgSrc, '/'));
    }
@endphp

<div class="grid grid-cols-1 gap-5 md:grid-cols-2">
    <!-- Facility Title -->
    <div class="form-group">
        <label for="title" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
            Facility Name <span class="text-danger">*</span>
        </label>
        <input type="text" name="title" id="title" class="form-input" required maxlength="150"
            placeholder="e.g. Infinity Rooftop Pool"
            value="{{ old('title', $f?->title) }}" />
        @error('title')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Subtitle / Eyebrow Tag -->
    <div class="form-group">
        <label for="subtitle" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
            Subtitle / Tagline
        </label>
        <input type="text" name="subtitle" id="subtitle" class="form-input" maxlength="150"
            placeholder="e.g. Panoramic Ocean Views / Holistic Rejuvenation"
            value="{{ old('subtitle', $f?->subtitle) }}" />
        @error('subtitle')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Icon Identifier -->
    <div class="form-group">
        <label for="icon" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
            Icon Identifier
        </label>
        <select name="icon" id="icon" class="form-select">
            @php
                $currentIcon = old('icon', $f?->icon ?? 'Sparkles');
                $icons = [
                    'Waves' => 'Waves (Pool / Ocean)',
                    'Sparkles' => 'Sparkles (Spa / Wellness)',
                    'Dumbbell' => 'Dumbbell (Fitness / Gym)',
                    'Calendar' => 'Calendar (Events / Conference)',
                    'Utensils' => 'Utensils (Dining / Bar)',
                    'Wifi' => 'Wifi (High-Speed Internet)',
                    'Coffee' => 'Coffee (Lounge / Cafe)',
                    'ShieldCheck' => 'ShieldCheck (24/7 Security)',
                    'Car' => 'Car (Valet / Parking)',
                ];
            @endphp
            @foreach($icons as $iconKey => $iconLabel)
                <option value="{{ $iconKey }}" {{ $currentIcon === $iconKey ? 'selected' : '' }}>
                    {{ $iconLabel }}
                </option>
            @endforeach
        </select>
        @error('icon')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Opening Hours -->
    <div class="form-group">
        <label for="opening_hours" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
            Opening Hours
        </label>
        <input type="text" name="opening_hours" id="opening_hours" class="form-input" maxlength="100"
            placeholder="e.g. 6:00 AM - 10:00 PM / 24 Hours"
            value="{{ old('opening_hours', $f?->opening_hours) }}" />
        @error('opening_hours')
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
            value="{{ old('sort_order', $f?->sort_order ?? 0) }}" />
        @error('sort_order')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Active Status Toggle -->
    <div class="form-group flex items-center pt-6">
        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
            <input type="checkbox" name="is_active" value="1" class="form-checkbox text-primary rounded"
                {{ old('is_active', $f?->is_active ?? true) ? 'checked' : '' }} />
            <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">Publish this facility on the website</span>
        </label>
    </div>

    <!-- Image Upload with Preview -->
    <div class="form-group md:col-span-2 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/30 p-5 space-y-4">
        <div>
            <h4 class="text-sm font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                <span>📸</span> Facility Photo
            </h4>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Upload a high-resolution photo for this facility (auto-converted to WebP).</p>
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
                        placeholder="e.g. /images/facilities/pool.avif"
                        value="{{ old('image', $f?->image) }}" />
                    @error('image')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            @if($imgSrc)
                <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#1b2e4b] p-3 shadow-sm flex flex-col items-center text-center">
                    <div class="relative w-full aspect-[4/3] rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800 mb-2 border border-slate-200 dark:border-slate-700">
                        <img src="{{ $imgSrc }}" alt="{{ $f->title }}" class="w-full h-full object-cover" onerror="this.src='/favicon.ico'">
                    </div>
                    <span class="text-[11px] font-mono text-slate-500 dark:text-slate-400 truncate w-full mb-2" title="{{ $f->image }}">{{ basename($f->image) }}</span>
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
            Overview & Description
        </label>
        <textarea name="description" id="description" rows="4" class="form-textarea"
            placeholder="Describe the facility features, guest amenities, equipment, and unique perks...">{{ old('description', $f?->description) }}</textarea>
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
            placeholder="Temperature Controlled&#10;Panoramic Sea View&#10;Private Cabanas & Sun Loungers&#10;Towel & Refreshment Service">{{ old('features_text', $featureLines) }}</textarea>
        @error('features_text')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>
</div>
