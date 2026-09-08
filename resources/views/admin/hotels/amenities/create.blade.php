@extends('admin.layouts.master')

@section('title', 'Add Amenity — '.$hotel->name)

@section('content')
    <div>
        <h2 class="text-xl font-semibold uppercase">Add Amenity</h2>
        <p class="text-sm text-white-dark">{{ $hotel->name }}</p>
    </div>

    <div class="panel mt-6">
        <div class="mb-8 rounded-lg bg-info/5 p-4 ring-1 ring-info/20">
            <h4 class="mb-1 text-sm font-bold uppercase text-info">Quick Select Common Amenities</h4>
            <p class="text-xs text-info/70 mb-4">Click to select multiple amenities and add them all in one click!</p>
            <div class="flex flex-wrap gap-2">
                @php
                    $commons = [
                        ['name' => 'Free WiFi', 'icon' => 'fas fa-wifi'],
                        ['name' => 'Swimming Pool', 'icon' => 'fas fa-swimming-pool'],
                        ['name' => 'Fitness Center', 'icon' => 'fas fa-dumbbell'],
                        ['name' => 'Restaurant', 'icon' => 'fas fa-utensils'],
                        ['name' => 'Room Service', 'icon' => 'fas fa-concierge-bell'],
                        ['name' => 'Free Parking', 'icon' => 'fas fa-parking'],
                        ['name' => 'Air Conditioning', 'icon' => 'fas fa-snowflake'],
                        ['name' => 'Spa & Wellness', 'icon' => 'fas fa-spa'],
                        ['name' => 'Housekeeping', 'icon' => 'fas fa-broom'],
                        ['name' => 'TV', 'icon' => 'fas fa-tv'],
                        ['name' => 'Hot Water', 'icon' => 'fas fa-shower'],
                        ['name' => 'Blanket', 'icon' => 'fas fa-bed'],
                        ['name' => 'Mini Fridge', 'icon' => 'fas fa-snowflake'],
                        ['name' => 'Hairdryer', 'icon' => 'fas fa-wind'],
                    ];
                @endphp
                @foreach ($commons as $item)
                    <button type="button" 
                        onclick="toggleAmenitySelection(this, '{{ $item['name'] }}', '{{ $item['icon'] }}')"
                        class="btn btn-xs btn-outline-info flex items-center gap-1.5 transition-all duration-200"
                        data-name="{{ $item['name'] }}"
                        data-icon="{{ $item['icon'] }}"
                        data-selected="false">
                        <span class="checkbox-indicator">⬜</span>
                        <span>{{ $item['name'] }}</span>
                    </button>
                @endforeach
            </div>

            <!-- Dynamic Bulk Submit Action Button -->
            <div id="bulk-action-container" class="mt-5 hidden transition-all duration-300">
                <button type="button" onclick="submitBulkAmenities()" class="btn btn-info flex items-center gap-2 shadow-md shadow-info/20">
                    <span>⚡</span> Add <span id="selected-count" class="badge bg-white text-info font-black">0</span> Selected Amenities in Bulk
                </button>
            </div>

            <!-- Hidden Bulk Submit Form -->
            <form id="bulk-amenity-form" action="{{ route('admin.hotels.amenities.store', $hotel) }}" method="POST" class="hidden">
                @csrf
                <div id="bulk-inputs-container"></div>
            </form>
        </div>

        <form action="{{ route('admin.hotels.amenities.store', $hotel) }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group">
                    <label for="amenity_name">Amenity Name <span class="text-danger">*</span></label>
                    <input type="text" name="amenity_name" id="amenity_name" class="form-input" required maxlength="100"
                        placeholder="e.g. Free WiFi, Swimming Pool" />
                </div>
                <div class="form-group">
                    <label for="icon">Icon Class (optional)</label>
                    <input type="text" name="icon" id="icon" class="form-input" maxlength="50"
                        placeholder="e.g. fas fa-wifi" />
                </div>
                <div class="form-group md:col-span-2">
                    <label for="description">Description (optional)</label>
                    <div id="amenity-editor-wrapper" class="richtext-wrapper">
                        <div id="quill-description-editor" style="height: 200px; background: #fff;" class="bg-white text-black dark:text-white rounded-t-lg border-slate-200"></div>
                        <textarea name="description" id="description" class="hidden">{{ old('description') }}</textarea>
                    </div>
                </div>
                <div class="form-group flex items-center gap-2">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1" class="form-checkbox" />
                    <label for="is_featured" class="!mb-0 font-semibold">Featured Amenity</label>
                </div>
            </div>
            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary">Add Amenity</button>
                <a href="{{ route('admin.hotels.amenities.index', $hotel) }}" class="btn btn-outline-danger">Cancel</a>
            </div>
        </form>
    </div>
@endsection

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
    // Bulk Amenity Selection Logic
    let selectedAmenities = [];

    function toggleAmenitySelection(button, name, icon) {
        const isSelected = button.getAttribute('data-selected') === 'true';
        
        if (isSelected) {
            button.setAttribute('data-selected', 'false');
            button.className = "btn btn-xs btn-outline-info flex items-center gap-1.5 transition-all duration-200";
            button.querySelector('.checkbox-indicator').innerText = "⬜";
            
            selectedAmenities = selectedAmenities.filter(item => item.name !== name);
        } else {
            button.setAttribute('data-selected', 'true');
            button.className = "btn btn-xs btn-info flex items-center gap-1.5 transition-all duration-200 text-white shadow-sm";
            button.querySelector('.checkbox-indicator').innerText = "✅";
            
            selectedAmenities.push({ name, icon });
        }

        const bulkContainer = document.getElementById('bulk-action-container');
        const selectedCount = document.getElementById('selected-count');
        
        if (selectedAmenities.length > 0) {
            bulkContainer.classList.remove('hidden');
            selectedCount.innerText = selectedAmenities.length;
        } else {
            bulkContainer.classList.add('hidden');
        }
    }

    function submitBulkAmenities() {
        if (selectedAmenities.length === 0) return;
        
        const container = document.getElementById('bulk-inputs-container');
        container.innerHTML = ''; 
        
        selectedAmenities.forEach((item, index) => {
            const nameInput = document.createElement('input');
            nameInput.type = 'hidden';
            nameInput.name = `bulk_amenities[${index}][name]`;
            nameInput.value = item.name;
            container.appendChild(nameInput);
            
            const iconInput = document.createElement('input');
            iconInput.type = 'hidden';
            iconInput.name = `bulk_amenities[${index}][icon]`;
            iconInput.value = item.icon;
            container.appendChild(iconInput);
        });
        
        document.getElementById('bulk-amenity-form').submit();
    }

    document.addEventListener('DOMContentLoaded', function () {
        var quill = new Quill('#quill-description-editor', {
            theme: 'snow',
            placeholder: 'Additional details about this amenity...',
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
