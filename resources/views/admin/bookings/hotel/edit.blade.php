@extends('admin.layouts.master')

@section('title', 'Edit Temporary Request - ' . $booking->reference_no)

@section('content')
    @php
        $leadPassenger = $booking->passengers->firstWhere('is_lead_passenger', true) ?? $booking->passengers->first();
        $roomTypes = $booking->hotelBooking->hotel->roomTypes->map(function ($roomType) {
            return [
                'id' => $roomType->id,
                'name' => $roomType->name,
                'price' => (float) $roomType->base_price_per_night,
            ];
        })->values();
        $defaultPassengers = old('passengers', $booking->passengers->map(function ($p) {
            return [
                'first_name' => $p->first_name,
                'last_name' => $p->last_name,
                'type' => $p->type === 'child' ? 'child' : 'adult',
            ];
        })->values()->toArray());
        if (count($defaultPassengers) === 0) {
            $defaultPassengers[] = ['first_name' => '', 'last_name' => '', 'type' => 'adult'];
        }
    @endphp

    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Temporary Request: <span class="text-primary">{{ $booking->reference_no }}</span></h2>
        <a href="{{ route('admin.bookings.hotel.show', $booking) }}" class="btn btn-outline-primary">Back to Details</a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.bookings.hotel.update', $booking) }}" method="POST" class="space-y-6" id="temp-request-edit-form">
            @csrf
            @method('PUT')

            <div class="rounded-md border border-primary/30 bg-primary/5 p-3 text-sm">
                Quick booking temporary request: hotel is fixed, but room type, stay, rooms, guests, and pricing are editable.
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                <div>
                    <label class="form-label">Primary Contact Name</label>
                    <input type="text" name="full_name" class="form-input" value="{{ old('full_name', trim(($leadPassenger->first_name ?? '') . ' ' . ($leadPassenger->last_name ?? ''))) }}" required>
                    @error('full_name') <p class="mt-1 text-danger text-xs">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Primary Contact Email</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email', $leadPassenger->email ?? $booking->user->email ?? '') }}" required>
                    @error('email') <p class="mt-1 text-danger text-xs">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Primary Contact Phone</label>
                    <input type="text" name="phone" class="form-input" value="{{ old('phone', $leadPassenger->phone ?? $booking->user->phone ?? '') }}" required>
                    @error('phone') <p class="mt-1 text-danger text-xs">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-4">
                <div>
                    <label class="form-label">Check In</label>
                    <input type="date" name="check_in" id="check_in" class="form-input" value="{{ old('check_in', optional($booking->hotelBooking->check_in)->format('Y-m-d')) }}" required>
                    @error('check_in') <p class="mt-1 text-danger text-xs">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Check Out</label>
                    <input type="date" name="check_out" id="check_out" class="form-input" value="{{ old('check_out', optional($booking->hotelBooking->check_out)->format('Y-m-d')) }}" required>
                    @error('check_out') <p class="mt-1 text-danger text-xs">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Rooms</label>
                    <input type="number" min="1" name="rooms_count" id="rooms_count" class="form-input" value="{{ old('rooms_count', $booking->hotelBooking->rooms_count) }}" required>
                    @error('rooms_count') <p class="mt-1 text-danger text-xs">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="pending" {{ old('status', $booking->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ old('status', $booking->status) === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="completed" {{ old('status', $booking->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status', $booking->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status') <p class="mt-1 text-danger text-xs">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                <div>
                    <label class="form-label">Hotel (fixed)</label>
                    <input type="text" class="form-input" value="{{ $booking->hotelBooking->hotel->name }}" readonly>
                </div>
                <div>
                    <label class="form-label">Room Type</label>
                    <select name="room_type_id" id="room_type_id" class="form-select" required>
                        @foreach($roomTypes as $rt)
                            <option value="{{ $rt['id'] }}"
                                data-price="{{ $rt['price'] }}"
                                {{ (string) old('room_type_id', $booking->hotelBooking->room_type_id) === (string) $rt['id'] ? 'selected' : '' }}>
                                {{ $rt['name'] }} ({{ number_format($rt['price'], 2) }} {{ $booking->currency }})
                            </option>
                        @endforeach
                    </select>
                    @error('room_type_id') <p class="mt-1 text-danger text-xs">{{ $message }}</p> @enderror
                </div>
                <div class="rounded border border-white-light p-3 text-sm">
                    <div class="flex justify-between"><span class="text-white-dark">Nights</span><span id="js-nights">{{ $booking->hotelBooking->nights }}</span></div>
                    <div class="mt-1 flex justify-between"><span class="text-white-dark">Adults / Children</span><span id="js-guest-counts">{{ $booking->hotelBooking->adult_guests }} / {{ $booking->hotelBooking->child_guests }}</span></div>
                    <div class="mt-1 flex justify-between"><span class="text-white-dark">Estimated Total</span><span id="js-estimated-total" class="font-bold text-primary">{{ number_format($booking->net_amount, 2) }} {{ $booking->currency }}</span></div>
                </div>
            </div>

            <div>
                <div class="mb-3 flex items-center justify-between">
                    <label class="form-label mb-0">Guest List Details</label>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="add-guest-row">Add Guest Row</button>
                </div>
                @error('passengers') <p class="mb-2 text-danger text-xs">{{ $message }}</p> @enderror
                <div class="overflow-x-auto">
                    <table class="table-hover w-full table-auto text-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="guest-rows">
                            @foreach($defaultPassengers as $i => $passenger)
                                <tr class="guest-row">
                                    <td class="row-index">{{ $i + 1 }}</td>
                                    <td>
                                        <select name="passengers[{{ $i }}][type]" class="form-select guest-type" required>
                                            <option value="adult" {{ ($passenger['type'] ?? 'adult') === 'adult' ? 'selected' : '' }}>Adult</option>
                                            <option value="child" {{ ($passenger['type'] ?? '') === 'child' ? 'selected' : '' }}>Child</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="passengers[{{ $i }}][first_name]" class="form-input" value="{{ $passenger['first_name'] ?? '' }}" required>
                                    </td>
                                    <td>
                                        <input type="text" name="passengers[{{ $i }}][last_name]" class="form-input" value="{{ $passenger['last_name'] ?? '' }}">
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-guest-row">Remove</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label class="form-label">Internal Notes</label>
                    <textarea name="notes" rows="3" class="form-textarea">{{ old('notes', $booking->notes) }}</textarea>
                    @error('notes') <p class="mt-1 text-danger text-xs">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Special Requests</label>
                    <textarea name="special_requests" rows="3" class="form-textarea">{{ old('special_requests', $booking->hotelBooking->special_requests) }}</textarea>
                    @error('special_requests') <p class="mt-1 text-danger text-xs">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.bookings.hotel.show', $booking) }}" class="btn btn-outline-primary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Temporary Request</button>
            </div>
        </form>
    </div>

    <script>
        (function () {
            const rowsEl = document.getElementById('guest-rows');
            const addBtn = document.getElementById('add-guest-row');
            const nightsEl = document.getElementById('js-nights');
            const guestCountsEl = document.getElementById('js-guest-counts');
            const totalEl = document.getElementById('js-estimated-total');
            const checkInEl = document.getElementById('check_in');
            const checkOutEl = document.getElementById('check_out');
            const roomsEl = document.getElementById('rooms_count');
            const roomTypeEl = document.getElementById('room_type_id');
            const currency = @json($booking->currency);

            function rowTemplate(index) {
                return `<tr class="guest-row">
                    <td class="row-index">${index + 1}</td>
                    <td>
                        <select name="passengers[${index}][type]" class="form-select guest-type" required>
                            <option value="adult">Adult</option>
                            <option value="child">Child</option>
                        </select>
                    </td>
                    <td><input type="text" name="passengers[${index}][first_name]" class="form-input" required></td>
                    <td><input type="text" name="passengers[${index}][last_name]" class="form-input"></td>
                    <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger remove-guest-row">Remove</button></td>
                </tr>`;
            }

            function renumberRows() {
                const rows = rowsEl.querySelectorAll('.guest-row');
                rows.forEach((row, idx) => {
                    row.querySelector('.row-index').textContent = String(idx + 1);
                    row.querySelectorAll('input, select').forEach((field) => {
                        field.name = field.name.replace(/passengers\[\d+\]/, `passengers[${idx}]`);
                    });
                });
                recalcGuestCounts();
            }

            function recalcGuestCounts() {
                const types = rowsEl.querySelectorAll('.guest-type');
                let adults = 0;
                let children = 0;
                types.forEach((t) => {
                    if (t.value === 'child') {
                        children += 1;
                    } else {
                        adults += 1;
                    }
                });
                guestCountsEl.textContent = `${adults} / ${children}`;
            }

            function diffNights() {
                if (!checkInEl.value || !checkOutEl.value) return 0;
                const inDate = new Date(checkInEl.value + 'T00:00:00');
                const outDate = new Date(checkOutEl.value + 'T00:00:00');
                const diff = Math.floor((outDate - inDate) / (1000 * 60 * 60 * 24));
                return diff > 0 ? diff : 0;
            }

            function recalcPrice() {
                const nights = diffNights();
                const rooms = Math.max(1, parseInt(roomsEl.value || '1', 10));
                const selected = roomTypeEl.options[roomTypeEl.selectedIndex];
                const pricePerNight = parseFloat(selected?.dataset?.price || '0');
                const total = nights * rooms * pricePerNight;
                nightsEl.textContent = String(nights);
                totalEl.textContent = `${total.toFixed(2)} ${currency}`;
            }

            addBtn.addEventListener('click', function () {
                const idx = rowsEl.querySelectorAll('.guest-row').length;
                rowsEl.insertAdjacentHTML('beforeend', rowTemplate(idx));
                renumberRows();
            });

            rowsEl.addEventListener('click', function (e) {
                const target = e.target;
                if (!target.classList.contains('remove-guest-row')) return;
                const rows = rowsEl.querySelectorAll('.guest-row');
                if (rows.length <= 1) return;
                target.closest('.guest-row').remove();
                renumberRows();
            });

            rowsEl.addEventListener('change', function (e) {
                if (e.target.classList.contains('guest-type')) recalcGuestCounts();
            });

            [checkInEl, checkOutEl, roomsEl, roomTypeEl].forEach((el) => {
                el.addEventListener('change', recalcPrice);
                el.addEventListener('input', recalcPrice);
            });

            renumberRows();
            recalcPrice();
        })();
    </script>
@endsection
