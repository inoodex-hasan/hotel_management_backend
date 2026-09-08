@extends('admin.layouts.master')

@section('title', 'Add Policy — '.$hotel->name)

@section('content')
    <div>
        <h2 class="text-xl font-semibold uppercase">Add Hotel Policy</h2>
        <p class="text-sm text-white-dark">{{ $hotel->name }}</p>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.hotels.policies.store', $hotel) }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group">
                    <label for="category">Policy Category <span class="text-danger">*</span></label>
                    <select name="category" id="category" class="form-select" required>
                        <option value="">Select category</option>
                        @foreach ($categories as $value => $label)
                            <option value="{{ $value }}" {{ old('category') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="title">Policy Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-input" required maxlength="200"
                        placeholder="e.g. Children up to 5 years stay free" value="{{ old('title') }}" />
                </div>
                <div class="form-group md:col-span-2">
                    <label for="description">Policy Description <span class="text-danger">*</span></label>
                    <div id="policy-editor-wrapper" class="richtext-wrapper">
                        <div id="quill-description-editor" style="height: 250px; background: #fff;" class="bg-white text-black dark:text-white rounded-t-lg border-slate-200"></div>
                        <textarea name="description" id="description" class="hidden" required>{{ old('description') }}</textarea>
                    </div>
                </div>
                <div class="form-group flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="is_active" value="1" class="form-checkbox" checked />
                    <label for="is_active" class="!mb-0 font-semibold">Active Policy</label>
                </div>
            </div>
            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary">Save Policy</button>
                <a href="{{ route('admin.hotels.policies.index', $hotel) }}" class="btn btn-outline-danger">Cancel</a>
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
    document.addEventListener('DOMContentLoaded', function () {
        var quill = new Quill('#quill-description-editor', {
            theme: 'snow',
            placeholder: 'Provide detailed information about this policy...',
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

        // Load initial content from old input
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
