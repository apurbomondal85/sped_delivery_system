@extends('layouts.master')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Delivery Zones</h3>
        <a href="{{ route('zones.create') }}" class="btn btn-primary">+ Add Zone</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        @foreach($zones as $zone)
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $zone->name ?? 'Unnamed Zone' }}</h5>
                        <p class="card-text">
                            Type: <strong>{{ ucfirst($zone->type) }}</strong><br>
                            @if($zone->type === 'radius')
                                Center: {{ $zone->center_lat }}, {{ $zone->center_lng }}<br>
                                Radius: {{ $zone->radius_meters }} meters
                            @else
                                Polygon Points: {{ count($zone->coordinates ?? []) }}
                            @endif
                        </p>
                        <div id="map-{{ $zone->id }}" style="height:250px; border-radius:8px;"></div>
                        <div class="mt-2">
                            <a href="{{ route('zones.edit', $zone->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>

<script>
document.addEventListener('DOMContentLoaded', function () {
    @foreach($zones as $zone)
        const map{{ $zone->id }} = L.map('map-{{ $zone->id }}').setView([
            {{ $zone->type === 'radius' ? $zone->center_lat : ($zone->coordinates[0][0] ?? 23.7808875) }},
            {{ $zone->type === 'radius' ? $zone->center_lng : ($zone->coordinates[0][1] ?? 90.2792371) }}
        ], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map{{ $zone->id }});

        @if($zone->type === 'polygon')
            const polygon{{ $zone->id }} = L.polygon({!! json_encode($zone->coordinates) !!}, {color:'blue', fillOpacity:0.3}).addTo(map{{ $zone->id }});
            map{{ $zone->id }}.fitBounds(polygon{{ $zone->id }}.getBounds());
        @elseif($zone->type === 'radius')
            const circle{{ $zone->id }} = L.circle([{{ $zone->center_lat }}, {{ $zone->center_lng }}], {
                radius: {{ $zone->radius_meters }},
                color: 'green',
                fillOpacity: 0.3
            }).addTo(map{{ $zone->id }});
            map{{ $zone->id }}.fitBounds(circle{{ $zone->id }}.getBounds());
        @endif
    @endforeach
});
</script>
@endpush
