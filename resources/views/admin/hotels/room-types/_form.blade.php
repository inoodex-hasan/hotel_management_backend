@php
    $r = $roomType;
    $currentGallery = is_array($r?->gallery) ? $r->gallery : (json_decode($r?->gallery ?? '[]', true) ?: []);
@endphp

<div class="grid grid-cols-1 gap-5 md:grid-cols-2">
    <!-- Room Name -->
    <div class="form-group">
        <label for="name">Room Name <span class="text-danger">*</span></label>
        <input type="text" name="name" id="name" class="form-input" required maxlength="150"
            placeholder="e.g. Premier Room"
            value="{{ old('name', $r?->name) }}" />
        @error('name')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Slug -->
    <div class="form-group">
        <label for="slug">Slug (URL identifier)</label>
        <input type="text" name="slug" id="slug" class="form-input" maxlength="150"
            placeholder="e.g. premier-room (auto-generated if empty)"
            value="{{ old('slug', $r?->slug) }}" />
        @error('slug')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Subtitle -->
    <div class="form-group">
        <label for="subtitle">Subtitle / Tagline</label>
        <input type="text" name="subtitle" id="subtitle" class="form-input" maxlength="200"
            placeholder="e.g. Comfort Redefined / Romantic Escape"
            value="{{ old('subtitle', $r?->subtitle) }}" />
        @error('subtitle')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Tag -->
    <div class="form-group">
        <label for="tag">Badge / Tag</label>
        <input type="text" name="tag" id="tag" class="form-input" maxlength="100"
            placeholder="e.g. Popular, Best Seller, Romantic, Luxury"
            value="{{ old('tag', $r?->tag) }}" />
        @error('tag')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Bed Type -->
    <div class="form-group">
        <label for="bed_type">Bed Type</label>
        <input type="text" name="bed_type" id="bed_type" class="form-input" maxlength="100"
            placeholder="e.g. 1 King Bed, 2 Twin Beds, 1 Queen Bed"
            value="{{ old('bed_type', $r?->bed_type) }}" />
        @error('bed_type')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Room Size -->
    <div class="form-group">
        <label for="room_size">Room Size</label>
        <input type="text" name="room_size" id="room_size" class="form-input" maxlength="100"
            placeholder="e.g. 320 sq ft, 450 sq ft, 1200 sq ft"
            value="{{ old('room_size', $r?->room_size) }}" />
        @error('room_size')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Floor -->
    <div class="form-group">
        <label for="floor">Floor / Location</label>
        <input type="text" name="floor" id="floor" class="form-input" maxlength="100"
            placeholder="e.g. 3rd - 5th Floor, 7th Floor"
            value="{{ old('floor', $r?->floor) }}" />
        @error('floor')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Stars -->
    <div class="form-group">
        <label for="stars">Star Rating (1 - 5)</label>
        <select name="stars" id="stars" class="form-select">
            <option value="5" {{ old('stars', $r?->stars ?? 5) == 5 ? 'selected' : '' }}>5 Stars</option>
            <option value="4" {{ old('stars', $r?->stars ?? 4) == 4 ? 'selected' : '' }}>4 Stars</option>
            <option value="3" {{ old('stars', $r?->stars ?? 3) == 3 ? 'selected' : '' }}>3 Stars</option>
        </select>
        @error('stars')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- SECTION: MAIN FEATURED IMAGE UPLOAD -->
    <div class="form-group md:col-span-2 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/30 p-5 space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="text-base font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                    <span>🖼️</span> Main Cover Image
                </h4>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Upload the primary photo displayed on room listings and booking cards.</p>
            </div>
            @if($r?->image)
                <span class="badge badge-outline-primary text-xs font-bold">Image Set</span>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
            <!-- File Upload Box -->
            <div class="md:col-span-2 space-y-2">
                <label for="image_file" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                    Upload New Main Image File <span class="text-xs font-normal text-slate-400">(JPG, PNG, WEBP, AVIF — Max 8MB)</span>
                </label>
                <input type="file" name="image_file" id="image_file"
                    class="form-input file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-primary file:text-white hover:file:bg-primary/90 cursor-pointer"
                    accept="image/*" />
                @error('image_file')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror

                <!-- URL fallback input -->
                <div class="pt-2">
                    <label for="image" class="block text-xs font-medium text-slate-500 dark:text-slate-400">
                        Or specify direct image URL / path (optional):
                    </label>
                    <input type="text" name="image" id="image" class="form-input text-xs"
                        placeholder="e.g. /images/room1.avif or https://..."
                        value="{{ old('image', $r?->image) }}" />
                    @error('image')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Current Main Image Preview -->
            @if($r?->image)
                <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#1b2e4b] p-3 shadow-sm flex flex-col items-center text-center">
                    <div class="relative w-full aspect-video rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800 mb-2 border border-slate-200 dark:border-slate-700">
                        <img src="{{ $r->image }}" alt="{{ $r->name }}" class="w-full h-full object-cover" onerror="this.src='/favicon.ico'">
                    </div>
                    <span class="text-[11px] font-mono text-slate-500 dark:text-slate-400 truncate w-full mb-2" title="{{ $r->image }}">{{ basename($r->image) }}</span>
                    <label class="inline-flex items-center gap-1.5 text-xs text-danger font-semibold cursor-pointer hover:underline">
                        <input type="checkbox" name="remove_main_image" value="1" class="form-checkbox text-danger rounded">
                        <span>Remove this image</span>
                    </label>
                </div>
            @endif
        </div>
    </div>

    <!-- SECTION: GALLERY PHOTOS MULTI-UPLOAD -->
    <div class="form-group md:col-span-2 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/30 p-5 space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="text-base font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                    <span>📸</span> Room Photo Gallery
                </h4>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Upload multiple photos for this room's detail gallery slider.</p>
            </div>
            <span class="badge bg-info/10 text-info border border-info/20 text-xs font-bold">
                {{ count($currentGallery) }} Current Photos
            </span>
        </div>

        <!-- Multi-file upload input -->
        <div class="space-y-2">
            <label for="gallery_files" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                Add More Photos <span class="text-xs font-normal text-slate-400">(Select multiple image files at once — Max 8MB each)</span>
            </label>
            <input type="file" name="gallery_files[]" id="gallery_files" multiple
                class="form-input file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-secondary file:text-white hover:file:bg-secondary/90 cursor-pointer"
                accept="image/*" />
            @error('gallery_files')
                <span class="text-danger text-sm">{{ $message }}</span>
            @enderror
            @error('gallery_files.*')
                <span class="text-danger text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Existing Gallery Photos Grid with Delete Checkboxes -->
        @if(count($currentGallery) > 0)
            <div class="space-y-2 pt-2">
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                    Existing Gallery Photos <span class="text-xs font-normal text-slate-400">(Check "Remove" to delete on save)</span>
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                    @foreach($currentGallery as $index => $photo)
                        <div x-data="{ marked: false }"
                            :class="marked ? 'border-danger/60 bg-danger/5 opacity-60' : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-[#1b2e4b]'"
                            class="group relative rounded-xl border p-2 shadow-sm flex flex-col justify-between transition-all duration-200">
                            <div class="relative w-full aspect-video rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800 mb-1.5">
                                <img src="{{ $photo }}" alt="Gallery Photo {{ $index + 1 }}" class="w-full h-full object-cover" onerror="this.src='/favicon.ico'">
                                <span class="absolute top-1 left-1 bg-black/60 text-white text-[10px] font-bold px-1.5 py-0.5 rounded">#{{ $index + 1 }}</span>
                                <div x-show="marked" x-cloak class="absolute inset-0 bg-danger/40 flex items-center justify-center font-bold text-white text-[11px] uppercase tracking-wider backdrop-blur-[1px]">
                                    Will Remove
                                </div>
                            </div>
                            <label class="inline-flex items-center gap-1.5 text-[11px] font-semibold cursor-pointer p-1 rounded transition select-none"
                                :class="marked ? 'text-danger bg-danger/10 font-bold' : 'text-slate-600 dark:text-slate-300 hover:text-danger hover:bg-danger/5'">
                                <input type="checkbox" name="remove_gallery[]" value="{{ $photo }}" @change="marked = $event.target.checked" class="form-checkbox text-danger rounded">
                                <span x-text="marked ? 'Marked for Removal' : 'Remove'">Remove</span>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Optional Manual Textarea for adding extra photo paths -->
        <div x-data="{ showTextarea: false }" class="pt-2 border-t border-slate-200 dark:border-slate-700/60">
            <button type="button" @click="showTextarea = !showTextarea" class="text-xs font-semibold text-primary hover:underline inline-flex items-center gap-1">
                <span x-text="showTextarea ? '▾ Hide manual URL lines' : '▸ Or add photo URLs manually'"></span>
            </button>
            <div x-show="showTextarea" x-cloak class="mt-2 space-y-1">
                <textarea name="gallery_text" id="gallery_text" rows="3" class="form-textarea text-xs font-mono"
                    placeholder="/images/rooms/room-1.avif&#10;/images/room1.avif">{{ old('gallery_text', '') }}</textarea>
                <p class="text-[11px] text-slate-400">Add extra image paths/URLs (one per line). Existing gallery items above are saved automatically unless marked for removal.</p>
            </div>
        </div>
    </div>

    <!-- Highlights -->
    <div class="form-group md:col-span-2">
        <label for="highlights_text">Highlights / Key Features (one per line)</label>
        @php
            $highlightLines = is_array($r?->highlights) ? implode("\n", $r->highlights) : ($r?->highlights ?? '');
        @endphp
        <textarea name="highlights_text" id="highlights_text" rows="5" class="form-textarea"
            placeholder="City View&#10;Premium Linens&#10;Blackout Curtains&#10;Work Desk">{{ old('highlights_text', $highlightLines) }}</textarea>
        @error('highlights_text')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Short Description -->
    <div class="form-group md:col-span-2">
        <label for="description">Short Description</label>
        <textarea name="description" id="description" rows="3" class="form-textarea"
            placeholder="Brief summary of the room...">{{ old('description', $r?->description) }}</textarea>
        @error('description')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Long Detailed Description -->
    <div class="form-group md:col-span-2">
        <label for="long_description">Long Detailed Description</label>
        <textarea name="long_description" id="long_description" rows="4" class="form-textarea"
            placeholder="Detailed story and experience of the room...">{{ old('long_description', $r?->long_description) }}</textarea>
        @error('long_description')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Capacity Adults -->
    <div class="form-group">
        <label for="capacity_adults">Adult Capacity <span class="text-danger">*</span></label>
        <input type="number" name="capacity_adults" id="capacity_adults" class="form-input" min="1" required
            value="{{ old('capacity_adults', $r?->capacity_adults ?? 2) }}" />
        @error('capacity_adults')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Capacity Children -->
    <div class="form-group">
        <label for="capacity_children">Children Capacity</label>
        <input type="number" name="capacity_children" id="capacity_children" class="form-input" min="0"
            value="{{ old('capacity_children', $r?->capacity_children ?? 0) }}" />
        @error('capacity_children')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Total Rooms -->
    <div class="form-group">
        <label for="total_rooms">Total Rooms <span class="text-danger">*</span></label>
        <input type="number" name="total_rooms" id="total_rooms" class="form-input" min="0" required
            value="{{ old('total_rooms', $r?->total_rooms ?? 10) }}" />
        @error('total_rooms')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Available Rooms -->
    <div class="form-group">
        <label for="available_rooms">Available Rooms <span class="text-danger">*</span></label>
        <input type="number" name="available_rooms" id="available_rooms" class="form-input" min="0" required
            value="{{ old('available_rooms', $r?->available_rooms ?? 10) }}" />
        @error('available_rooms')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Base Price -->
    <div class="form-group">
        <label for="base_price_per_night">Base Price / Night (BDT) <span class="text-danger">*</span></label>
        <input type="number" step="0.01" name="base_price_per_night" id="base_price_per_night" class="form-input" min="0"
            required value="{{ old('base_price_per_night', $r?->base_price_per_night ?? 7500) }}" />
        @error('base_price_per_night')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Refundable Checkbox -->
    <div class="form-group flex items-center gap-2 pt-6">
        <input type="hidden" name="is_refundable" value="0" />
        <input type="checkbox" name="is_refundable" id="is_refundable" value="1" class="form-checkbox"
            {{ old('is_refundable', $r?->is_refundable ?? true) ? 'checked' : '' }} />
        <label for="is_refundable" class="!mb-0 font-semibold cursor-pointer">Free Cancellation / Refundable</label>
        @error('is_refundable')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Cancellation Policy Note -->
    <div class="form-group md:col-span-2">
        <label for="cancellation_policy">Cancellation Policy Note</label>
        <textarea name="cancellation_policy" id="cancellation_policy" rows="2" class="form-textarea"
            placeholder="e.g. Free cancellation up to 24 hours before check-in.">{{ old('cancellation_policy', $r?->cancellation_policy) }}</textarea>
        @error('cancellation_policy')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>
</div>
