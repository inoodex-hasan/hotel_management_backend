<div class="grid grid-cols-1 gap-5 md:grid-cols-2">
    <div class="form-group md:col-span-2">
        <label for="country_id">Country <span class="text-danger">*</span></label>
        <select name="country_id" id="country_id" class="form-select" required>
            <option value="">Select country</option>
            @foreach ($countries as $country)
                <option value="{{ $country->id }}"
                    {{ (string) old('country_id', $city?->country_id) === (string) $country->id ? 'selected' : '' }}>
                    {{ $country->name }} ({{ $country->iso_code }})
                </option>
            @endforeach
        </select>
        @error('country_id')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group">
        <label for="name">City Name <span class="text-danger">*</span></label>
        <input type="text" name="name" id="name" class="form-input" required maxlength="100"
            value="{{ old('name', $city?->name) }}" />
        @error('name')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group">
        <label for="iata_code">Short Code</label>
        <input type="text" name="iata_code" id="iata_code" class="form-input" maxlength="5"
            placeholder="e.g. DAC, JFK" value="{{ old('iata_code', $city?->iata_code) }}" />
        @error('iata_code')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group md:col-span-2">
        <label for="photo">Photo</label>
        <input type="file" name="photo" id="photo" class="form-input" accept="image/*" />
        @if($city?->hasMedia('photo'))
            <div class="mt-2">
                <img src="{{ $city->getFirstMediaUrl('photo') }}" alt="Current photo" class="h-20 w-20 rounded object-cover">
            </div>
        @endif
        @error('photo')
            <span class="text-danger text-sm">{{ $message }}</span>
        @enderror
    </div>
</div>