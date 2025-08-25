@extends('layouts.master')

@section('content')
    <div class="container py-4">
        <h3>Create New Order</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('orders.store') }}">
            @csrf

            <div class="mb-3">
                <label for="user_id" class="form-label">Customer</label>
                <select name="user_id" id="user_id" class="form-select" required>
                    <option value="">Select Customer</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('user_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }} ({{ $customer->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <input type="hidden" name="restaurant_id" value="{{ auth()->user()->restaurant->id }}">

            <div class="mb-3">
                <label for="address" class="form-label">Delivery Address</label>
                <input type="text" name="address" id="address" class="form-control" value="{{ old('address') }}"
                    placeholder="Enter delivery address" required>
            </div>

            <input type="hidden" name="lat" id="lat" value="{{ old('lat') }}">
            <input type="hidden" name="lng" id="lng" value="{{ old('lng') }}">

            <div class="mb-3">
                <label class="form-label">Select Location on Map</label>
                <div id="map" style="height:400px; border-radius:8px;"></div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-success">Create Order</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const defaultLat = 23.7808875;
            const defaultLng = 90.2792371;

            const map = L.map('map').setView([defaultLat, defaultLng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            let marker;

            function setMarker(lat, lng) {
                if (marker) map.removeLayer(marker);
                marker = L.marker([lat, lng], {
                    draggable: true
                }).addTo(map);
                marker.on('dragend', function(e) {
                    const pos = e.target.getLatLng();
                    document.getElementById('lat').value = pos.lat.toFixed(7);
                    document.getElementById('lng').value = pos.lng.toFixed(7);
                });
                document.getElementById('lat').value = lat.toFixed(7);
                document.getElementById('lng').value = lng.toFixed(7);
            }

            map.on('click', function(e) {
                setMarker(e.latlng.lat, e.latlng.lng);
            });

            const oldLat = parseFloat(document.getElementById('lat').value);
            const oldLng = parseFloat(document.getElementById('lng').value);
            if (oldLat && oldLng) {
                setMarker(oldLat, oldLng);
                map.setView([oldLat, oldLng], 15);
            }
        });
    </script>
@endpush
