@csrf

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- LEFT 2 COLS: Core Details -->
    <div class="lg:col-span-2 space-y-6">
        <div class="panel">
            <h5 class="text-md font-bold uppercase tracking-wider text-slate-700 dark:text-white mb-5 pb-3 border-b border-slate-100 dark:border-slate-800">
                Photo Information
            </h5>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label for="title" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">
                        Photo Title <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title', $item->title) }}"
                        placeholder="e.g. Grand Lobby, Ocean View Suite, Sunset Terrace"
                        class="form-input text-sm @error('title') border-danger @enderror" required />
                    @error('title')
                        <span class="text-danger text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">
                        Category <span class="text-danger">*</span>
                    </label>
                    <input list="category-list" type="text" name="category" id="category" value="{{ old('category', $item->category ?? 'Interior') }}"
                        placeholder="e.g. Interior, Rooms, Dining, Wellness, Experience, Lifestyle"
                        class="form-input text-sm @error('category') border-danger @enderror" required />
                    <datalist id="category-list">
                        <option value="Interior">
                        <option value="Rooms">
                        <option value="Dining">
                        <option value="Wellness">
                        <option value="Experience">
                        <option value="Lifestyle">
                        <option value="Exterior">
                        @foreach($existingCategories as $cat)
                            <option value="{{ $cat }}">
                        @endforeach
                    </datalist>
                    @error('category')
                        <span class="text-danger text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Hotel Assignment -->
                <div>
                    <label for="hotel_id" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">
                        Hotel Property
                    </label>
                    <select name="hotel_id" id="hotel_id" class="form-select text-sm @error('hotel_id') border-danger @enderror">
                        @foreach($hotels as $hotel)
                            <option value="{{ $hotel->id }}" {{ old('hotel_id', $item->hotel_id) == $hotel->id ? 'selected' : '' }}>
                                {{ $hotel->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('hotel_id')
                        <span class="text-danger text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Sort Order -->
                <div>
                    <label for="sort_order" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">
                        Sort Order
                    </label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $item->sort_order ?? 0) }}"
                        min="0" class="form-input text-sm @error('sort_order') border-danger @enderror" />
                    <span class="text-[11px] text-slate-400 mt-1 block">Lower numbers display first</span>
                    @error('sort_order')
                        <span class="text-danger text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT 1 COL: Image & Status -->
    <div class="space-y-6">
        <!-- Photo Upload Panel -->
        <div class="panel">
            <h5 class="text-md font-bold uppercase tracking-wider text-slate-700 dark:text-white mb-4 pb-2 border-b border-slate-100 dark:border-slate-800">
                Photo Image
            </h5>

            @php
                $currentImage = $item->image;
                if ($currentImage && (str_starts_with($currentImage, '/storage/') || str_starts_with($currentImage, 'storage/'))) {
                    $currentImage = url(ltrim($currentImage, '/'));
                }
            @endphp

            @if($currentImage)
                <div class="mb-4">
                    <span class="text-xs font-bold uppercase text-slate-500 block mb-2">Current Photo:</span>
                    <div class="relative w-full h-48 rounded-xl overflow-hidden border border-slate-200 bg-slate-100 shadow-sm">
                        <img src="{{ $currentImage }}" alt="{{ $item->title }}" class="w-full h-full object-cover" />
                    </div>
                    <div class="mt-3 flex items-center gap-2">
                        <label class="inline-flex items-center text-xs text-danger font-semibold cursor-pointer">
                            <input type="checkbox" name="remove_image" value="1" class="form-checkbox text-danger rounded mr-1.5" />
                            Remove this photo
                        </label>
                    </div>
                </div>
            @endif

            <div class="space-y-3">
                <div>
                    <label for="image_file" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">
                        Upload New Photo (WebP auto-optimized)
                    </label>
                    <input type="file" name="image_file" id="image_file" accept="image/*"
                        class="form-input file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 text-xs" />
                    <span class="text-[11px] text-slate-400 mt-1 block">Supports JPG, PNG, WebP, AVIF up to 8MB</span>
                    @error('image_file')
                        <span class="text-danger text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="relative flex py-1 items-center">
                    <div class="flex-grow border-t border-slate-200 dark:border-slate-700"></div>
                    <span class="flex-shrink mx-2 text-[10px] text-slate-400 uppercase font-semibold">or direct URL / path</span>
                    <div class="flex-grow border-t border-slate-200 dark:border-slate-700"></div>
                </div>

                <div>
                    <label for="image" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">
                        Image URL / Static Path
                    </label>
                    <input type="text" name="image" id="image" value="{{ old('image', $item->image) }}"
                        placeholder="/images/room1.avif or https://..."
                        class="form-input text-xs @error('image') border-danger @enderror" />
                    @error('image')
                        <span class="text-danger text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Publish Status & Action -->
        <div class="panel">
            <h5 class="text-md font-bold uppercase tracking-wider text-slate-700 dark:text-white mb-4 pb-2 border-b border-slate-100 dark:border-slate-800">
                Publish Status
            </h5>

            <div class="mb-5">
                <label class="relative flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="form-checkbox text-primary rounded h-5 w-5"
                        {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }} />
                    <span class="ml-2.5 text-sm font-semibold text-slate-700 dark:text-slate-200">
                        Active & Visible in Gallery
                    </span>
                </label>
                <span class="text-[11px] text-slate-400 ml-7 block mt-1">If unchecked, this photo will be hidden from the live website</span>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="submit" class="btn btn-primary flex-1">
                    {{ $item->exists ? 'Update Gallery Photo' : 'Save Gallery Photo' }}
                </button>
                <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline-secondary">
                    Cancel
                </a>
            </div>
        </div>
    </div>
</div>
