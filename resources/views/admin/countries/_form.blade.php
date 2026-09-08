<div class="grid grid-cols-1 gap-5 md:grid-cols-2">
    <div class="form-group md:col-span-2">
        <label for="name">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" id="name" class="form-input" required maxlength="100"
            value="{{ old('name', $country?->name) }}" />
        @error('name')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group">
        <label for="iso_code">Short Code <span class="text-danger">*</span></label>
        <input type="text" name="iso_code" id="iso_code" class="form-input" required maxlength="3"
            placeholder="e.g. BD, US" value="{{ old('iso_code', $country?->iso_code) }}" />
        @error('iso_code')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group">
        <label for="phone_code">Phone Code</label>
        <input type="text" name="phone_code" id="phone_code" class="form-input" maxlength="10"
            placeholder="e.g. +880" value="{{ old('phone_code', $country?->phone_code) }}" />
        @error('phone_code')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>
</div>
