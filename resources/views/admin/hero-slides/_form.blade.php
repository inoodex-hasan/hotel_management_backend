@php
    $isEdit = isset($heroSlide);
@endphp

<div class="space-y-6">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Main Form Details -->
        <div class="md:col-span-2 space-y-5">
            <div class="panel">
                <h3 class="text-base font-bold text-slate-800 dark:text-white mb-4">Slide Content</h3>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="hotel_id" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">Hotel (Optional)</label>
                            <select id="hotel_id" name="hotel_id" class="form-select mt-1">
                                <option value="">Global / All Hotels</option>
                                @foreach($hotels as $h)
                                    <option value="{{ $h->id }}" {{ old('hotel_id', $heroSlide->hotel_id ?? '') == $h->id ? 'selected' : '' }}>
                                        {{ $h->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="order" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">Display Order</label>
                            <input type="number" id="order" name="order" class="form-input mt-1" value="{{ old('order', $heroSlide->order ?? 1) }}" min="0" />
                        </div>
                    </div>

                    <div>
                        <label for="title" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">Slide Heading / Title</label>
                        <input type="text" id="title" name="title" class="form-input mt-1" value="{{ old('title', $heroSlide->title ?? '') }}" placeholder="e.g. Escape to Refinement & Grace" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="subtitle" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">Subtitle / Eyebrow</label>
                            <input type="text" id="subtitle" name="subtitle" class="form-input mt-1" value="{{ old('subtitle', $heroSlide->subtitle ?? '') }}" placeholder="e.g. Welcome to The Azura" />
                        </div>
                        <div>
                            <label for="badge_text" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">Badge Text</label>
                            <input type="text" id="badge_text" name="badge_text" class="form-input mt-1" value="{{ old('badge_text', $heroSlide->badge_text ?? '') }}" placeholder="e.g. 5-Star Luxury Experience" />
                        </div>
                    </div>

                    <div>
                        <label for="description" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">Description</label>
                        <textarea id="description" name="description" rows="3" class="form-textarea mt-1" placeholder="Brief tagline or description for the hero slide...">{{ old('description', $heroSlide->description ?? '') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="button_text" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">Call-to-Action Button Text</label>
                            <input type="text" id="button_text" name="button_text" class="form-input mt-1" value="{{ old('button_text', $heroSlide->button_text ?? '') }}" placeholder="e.g. Explore Suites" />
                        </div>
                        <div>
                            <label for="button_link" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">Button Link URL</label>
                            <input type="text" id="button_link" name="button_link" class="form-input mt-1" value="{{ old('button_link', $heroSlide->button_link ?? '') }}" placeholder="e.g. /rooms or /booking" />
                        </div>
                    </div>

                    <div class="pt-2">
                        <label class="flex items-center cursor-pointer">
                            <input type="hidden" name="is_active" value="0" />
                            <input type="checkbox" name="is_active" value="1" class="form-checkbox text-primary rounded" {{ old('is_active', $heroSlide->is_active ?? true) ? 'checked' : '' }} />
                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-200 ltr:ml-2 rtl:mr-2">Active (Visible on frontend Hero slider)</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Upload & Preview Sidebar -->
        <div class="space-y-5">
            <div class="panel">
                <h3 class="text-base font-bold text-slate-800 dark:text-white mb-3">Slide Image</h3>
                <p class="text-xs text-slate-400 mb-4">Upload a high-res image (auto-converted to WebP 1920px max) or specify an image URL.</p>

                <div class="space-y-4">
                    <!-- Current Image Preview -->
                    <div>
                        <span class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300 block mb-2">Image Preview</span>
                        <div class="relative w-full h-44 rounded-2xl overflow-hidden border-2 border-dashed border-slate-200 bg-slate-50 dark:bg-dark dark:border-slate-700 flex items-center justify-center group shadow-sm">
                            <img id="heroImagePreview"
                                 src="{{ $isEdit && $heroSlide->image_url ? $heroSlide->image_full_url : 'https://images.unsplash.com/photo-1590490360182-c33d57733427' }}"
                                 alt="Preview"
                                 class="w-full h-full object-cover {{ $isEdit && $heroSlide->image_url ? '' : 'opacity-60' }}" />
                        </div>
                    </div>

                    <!-- File Upload Input -->
                    <div>
                        <label for="image_file" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300 block mb-1">
                            Upload New Image
                        </label>
                        <input type="file" id="image_file" name="image_file" accept="image/*" class="form-input file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer" onchange="previewHeroImage(event)" />
                        <span class="text-[11px] text-slate-400 mt-1 block">JPG, PNG, WebP, AVIF up to 5MB. Automatically optimized and converted to WebP.</span>
                    </div>

                    <!-- Fallback URL Input -->
                    <div class="pt-2 border-t border-slate-100 dark:border-slate-700">
                        <label for="image_url" class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300 block mb-1">
                            Or Image URL / Existing Path
                        </label>
                        <input type="text" id="image_url" name="image_url" class="form-input" value="{{ old('image_url', $heroSlide->image_url ?? '') }}" placeholder="/images/room1.avif or https://..." />
                    </div>
                </div>
            </div>

            <!-- Submit Button Card -->
            <div class="panel">
                <button type="submit" class="btn btn-primary w-full py-3 text-sm font-bold shadow-lg shadow-primary/20">
                    {{ $isEdit ? 'Save Slide Changes' : 'Create Hero Slide' }}
                </button>
                <a href="{{ route('admin.hero-slides.index') }}" class="btn btn-outline-secondary w-full mt-2 text-center block text-xs">
                    Cancel & Return
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function previewHeroImage(event) {
    const input = event.target;
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('heroImagePreview');
            preview.src = e.target.result;
            preview.classList.remove('opacity-60');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
