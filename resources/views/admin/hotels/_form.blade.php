@php
    $h = $hotel;
@endphp
<div class="grid grid-cols-1 gap-5 md:grid-cols-2">
    <div class="form-group md:col-span-2">
        <label for="city_id">City <span class="text-danger">*</span></label>
        <select name="city_id" id="city_id" class="form-select" required>
            <option value="">Select city</option>
            @foreach ($cities as $city)
                <option value="{{ $city->id }}"
                    {{ (string) old('city_id', $h?->city_id) === (string) $city->id ? 'selected' : '' }}>
                    {{ $city->name }} ({{ $city->country->iso_code }})
                </option>
            @endforeach
        </select>
        @error('city_id')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group md:col-span-2">
        <label for="name">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" id="name" class="form-input" required maxlength="200"
            value="{{ old('name', $h?->name) }}" />
        @error('name')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group md:col-span-2">
        <label for="slug">Slug (URL Keyword) <span class="text-info text-xs">(Auto-generated if empty)</span></label>
        <input type="text" name="slug" id="slug" class="form-input" maxlength="255"
            placeholder="e.g. grand-palace-hotel" value="{{ old('slug', $h?->slug) }}" />
        @error('slug')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group md:col-span-2">
        <label for="description">Description</label>
        <div id="hotel-editor-wrapper" class="richtext-wrapper">
            <div id="quill-description-editor" style="height: 250px; background: #fff;" class="bg-white text-black dark:text-white rounded-t-lg border-slate-200"></div>
            <textarea name="description" id="description" class="hidden">{{ old('description', $h?->description) }}</textarea>
        </div>
        @error('description')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group">
        <label for="star_rating">Star rating <span class="text-danger">*</span></label>
        <input type="number" name="star_rating" id="star_rating" class="form-input" min="1" max="5" required
            value="{{ old('star_rating', $h?->star_rating) }}" />
        @error('star_rating')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group">
        <label for="status">Status <span class="text-danger">*</span></label>
        <select name="status" id="status" class="form-select" required>
            @foreach (['active', 'inactive'] as $st)
                <option value="{{ $st }}" {{ old('status', $h?->status) === $st ? 'selected' : '' }}>{{ $st }}</option>
            @endforeach
        </select>
        @error('status')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group md:col-span-2">
        <label for="address">Address <span class="text-danger">*</span></label>
        <textarea name="address" id="address" class="form-textarea" rows="2" required>{{ old('address', $h?->address) }}</textarea>
        @error('address')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>
    <!-- <div class="form-group">
        <label for="latitude">Latitude</label>
        <input type="text" name="latitude" id="latitude" class="form-input" value="{{ old('latitude', $h?->latitude) }}" />
        @error('latitude')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group">
        <label for="longitude">Longitude</label>
        <input type="text" name="longitude" id="longitude" class="form-input" value="{{ old('longitude', $h?->longitude) }}" />
        @error('longitude')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div> -->
    <div class="form-group">
        <label for="contact_phone">Contact phone</label>
        <input type="text" name="contact_phone" id="contact_phone" class="form-input" maxlength="20"
            value="{{ old('contact_phone', $h?->contact_phone) }}" />
        @error('contact_phone')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group">
        <label for="contact_email">Contact email</label>
        <input type="email" name="contact_email" id="contact_email" class="form-input" maxlength="150"
            value="{{ old('contact_email', $h?->contact_email) }}" />
        @error('contact_email')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group">
        <label for="check_in_time">Check-in time</label>
        <input type="time" name="check_in_time" id="check_in_time" class="form-input"
            value="{{ old('check_in_time', $h && $h->check_in_time ? \Illuminate\Support\Str::substr((string) $h->check_in_time, 0, 5) : '') }}" />
        @error('check_in_time')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group">
        <label for="check_out_time">Check-out time</label>
        <input type="time" name="check_out_time" id="check_out_time" class="form-input"
            value="{{ old('check_out_time', $h && $h->check_out_time ? \Illuminate\Support\Str::substr((string) $h->check_out_time, 0, 5) : '') }}" />
        @error('check_out_time')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group md:col-span-2 flex items-center gap-2">
        <input type="hidden" name="is_international" value="0" />
        <input type="checkbox" name="is_international" id="is_international" value="1" class="form-checkbox"
            {{ old('is_international', $h?->is_international) ? 'checked' : '' }} />
        <label for="is_international" class="!mb-0">International property</label>
        @error('is_international')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- SEO Section -->
    <div class="md:col-span-2 mt-4">
        <h4 class="font-bold text-lg border-b border-gray-200 dark:border-[#1b2e4b] pb-2 mb-4">SEO Settings</h4>
    </div>
    
    <div class="form-group md:col-span-2">
        <label for="meta_title">Meta Title</label>
        <input type="text" name="meta_title" id="meta_title" class="form-input" maxlength="255"
            value="{{ old('meta_title', $h?->meta_title) }}" placeholder="SEO optimized title" />
        @error('meta_title')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group md:col-span-2">
        <label for="meta_description">Meta Description</label>
        <textarea name="meta_description" id="meta_description" class="form-textarea" rows="2" 
            placeholder="Brief summary for search results">{{ old('meta_description', $h?->meta_description) }}</textarea>
        @error('meta_description')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group md:col-span-2">
        <label for="meta_keywords">Meta Keywords</label>
        <textarea name="meta_keywords" id="meta_keywords" class="form-textarea" rows="2" 
            placeholder="luxury, hotel, stay, vacation (comma separated)">{{ old('meta_keywords', $h?->meta_keywords) }}</textarea>
        @error('meta_keywords')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>
</div>

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    /* Quill custom styles to match dark/light theme */
    .ql-toolbar.ql-snow {
        border-color: #e2e8f0 !important;
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
        background-color: #f8fafc;
    }
    .ql-container.ql-snow {
        border-color: #e2e8f0 !important;
        border-bottom-left-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
    }
    .dark .ql-toolbar.ql-snow {
        border-color: #3b3f5c !important;
        background-color: #1b2e4b;
    }
    .dark .ql-container.ql-snow {
        border-color: #3b3f5c !important;
        background-color: #0e1726;
        color: #888ea8;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var quill = new Quill('#quill-description-editor', {
            theme: 'snow',
            placeholder: 'Provide detailed information about this hotel...',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link', 'clean']
                ]
            }
        });
        
        var textarea = document.getElementById('description');

        // Load initial content
        if (textarea.value) {
            quill.root.innerHTML = textarea.value;
        }

        // Sync Quill HTML content back to hidden textarea on text change
        quill.on('text-change', function () {
            textarea.value = quill.root.innerHTML === '<p><br></p>' ? '' : quill.root.innerHTML;
        });
    });
</script>
@endpush
