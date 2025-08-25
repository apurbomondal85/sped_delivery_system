
@extends('layouts.master')

@section('content')
<div class="container py-4">
    <h3>Create Delivery Zone for: {{ $restaurant->name }}</h3>

    <form method="POST" action="{{ route('zones.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Zone Type</label>
            <select name="type" id="zoneType" class="form-select">
                <option value="polygon">Polygon</option>
                <option value="radius">Radius</option>
            </select>
        </div>

        <input type="hidden" name="coordinates" id="coordinates">
        <input type="hidden" name="center_lat" id="center_lat">
        <input type="hidden" name="center_lng" id="center_lng">
        <input type="hidden" name="radius_meters" id="radius_meters">

        <div id="map" style="height:500px; border-radius:10px; overflow:hidden;"></div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Save Zone</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-draw/dist/leaflet.draw.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<link rel="stylesheet" href="https://unpkg.com/leaflet-draw/dist/leaflet.draw.css"/>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const restLat = {{ $restaurant->lat ?? 23.7808875 }};
    const restLng = {{ $restaurant->lng ?? 90.2792371 }};

    const map = L.map('map').setView([restLat, restLng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    const drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);

    const drawControl = new L.Control.Draw({
        draw: {
            polygon: true,
            circle: true,
            marker: false,
            polyline: false,
            rectangle: false,
            circlemarker: false,
        },
        edit: { featureGroup: drawnItems }
    });
    map.addControl(drawControl);

    const zoneTypeEl = document.getElementById('zoneType');
    const coordsEl   = document.getElementById('coordinates');
    const cLatEl     = document.getElementById('center_lat');
    const cLngEl     = document.getElementById('center_lng');
    const rEl        = document.getElementById('radius_meters');

    function clearInputs() {
        coordsEl.value = '';
        cLatEl.value   = '';
        cLngEl.value   = '';
        rEl.value      = '';
    }

    map.on(L.Draw.Event.CREATED, function (e) {
        drawnItems.clearLayers();
        clearInputs();

        const layer = e.layer;
        drawnItems.addLayer(layer);

        if (zoneTypeEl.value === 'polygon' && layer instanceof L.Polygon) {
            const latlngs = layer.getLatLngs()[0].map(ll => [ll.lat, ll.lng]);
            coordsEl.value = JSON.stringify(latlngs);
        } 
        else if (zoneTypeEl.value === 'radius' && layer instanceof L.Circle) {
            const c = layer.getLatLng();
            cLatEl.value = c.lat.toFixed(7);
            cLngEl.value = c.lng.toFixed(7);
            rEl.value    = Math.round(layer.getRadius());
        } 
        else {
            alert('Please draw a ' + zoneTypeEl.value + ' shape.');
            drawnItems.removeLayer(layer);
        }
    });

    zoneTypeEl.addEventListener('change', () => {
        drawnItems.clearLayers();
        clearInputs();
    });

    L.marker([restLat, restLng]).addTo(map).bindPopup("Restaurant");
});
</script>
@endpush
