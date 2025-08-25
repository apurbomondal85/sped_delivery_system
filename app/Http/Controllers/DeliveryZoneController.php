<?php

namespace App\Http\Controllers;

use App\Models\DeliveryZone;
use Illuminate\Http\Request;

class DeliveryZoneController extends Controller
{
    public function index()
    {
        $restaurant = auth()->user()->restaurant;
        $zones = $restaurant->zones()->orderBy('created_at', 'desc')->get();

        return view('zones.index', compact('restaurant', 'zones'));
    }

    public function create()
    {
        $restaurant = auth()->user()->restaurant;

        return view('zones.create', compact('restaurant'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type'           => 'required|in:polygon,radius',
            'name'           => 'nullable|string|max:100',
            'coordinates'    => 'nullable|string',
            'center_lat'     => 'nullable|numeric',
            'center_lng'     => 'nullable|numeric',
            'radius_meters'  => 'nullable|integer|min:50|max:50000',
        ]);

        if ($data['type'] === 'polygon') {
            $coords = json_decode($data['coordinates'] ?? '[]', true);
            if (!is_array($coords) || count($coords) < 3) {
                return back()->withErrors(['coordinates' => 'Polygon must have at least 3 points.'])->withInput();
            }

            $lats = array_column($coords, 0);
            $lngs = array_column($coords, 1);

            $payload = [
                'type' => 'polygon',
                'coordinates' => $coords,
                'min_lat' => min($lats),
                'max_lat' => max($lats),
                'min_lng' => min($lngs),
                'max_lng' => max($lngs),
            ];
        } else {
            if (!isset($data['center_lat'], $data['center_lng'], $data['radius_meters'])) {
                return back()->withErrors(['radius' => 'Center and radius required for radius zone.'])->withInput();
            }

            $deg = $data['radius_meters'] / 111000;
            $payload = [
                'type' => 'radius',
                'center_lat' => (float)$data['center_lat'],
                'center_lng' => (float)$data['center_lng'],
                'radius_meters' => (int)$data['radius_meters'],
                'min_lat' => $data['center_lat'] - $deg,
                'max_lat' => $data['center_lat'] + $deg,
                'min_lng' => $data['center_lng'] - $deg,
                'max_lng' => $data['center_lng'] + $deg,
            ];
        }

        $zone = new DeliveryZone(array_merge([
            'restaurant_id' => auth()->user()->restaurant->id,
            'name' => $data['name'] ?? null,
        ], $payload));

        $zone->save();

        return redirect()->route('zones.index')
            ->with('success', 'Delivery zone created successfully.');
    }

    public function edit(DeliveryZone $zone)
    {
        return view('zones.update', compact('zone'));
    }

    public function update(Request $request, DeliveryZone $zone)
    {
        $data = $request->validate([
            'type'           => 'required|in:polygon,radius',
            'name'           => 'nullable|string|max:100',
            'coordinates'    => 'nullable|string',
            'center_lat'     => 'nullable|numeric',
            'center_lng'     => 'nullable|numeric',
            'radius_meters'  => 'nullable|integer|min:50|max:50000',
        ]);

        if ($data['type'] === 'polygon') {
            $coords = json_decode($data['coordinates'] ?? '[]', true);
            if (!is_array($coords) || count($coords) < 3) {
                return back()->withErrors(['coordinates' => 'Polygon must have at least 3 points.'])->withInput();
            }

            $lats = array_column($coords, 0);
            $lngs = array_column($coords, 1);

            $payload = [
                'type' => 'polygon',
                'coordinates' => $coords,
                'min_lat' => min($lats),
                'max_lat' => max($lats),
                'min_lng' => min($lngs),
                'max_lng' => max($lngs),
            ];
        } else {
            if (!isset($data['center_lat'], $data['center_lng'], $data['radius_meters'])) {
                return back()->withErrors(['radius' => 'Center and radius required for radius zone.'])->withInput();
            }

            $deg = $data['radius_meters'] / 111000;
            $payload = [
                'type' => 'radius',
                'center_lat' => (float)$data['center_lat'],
                'center_lng' => (float)$data['center_lng'],
                'radius_meters' => (int)$data['radius_meters'],
                'min_lat' => $data['center_lat'] - $deg,
                'max_lat' => $data['center_lat'] + $deg,
                'min_lng' => $data['center_lng'] - $deg,
                'max_lng' => $data['center_lng'] + $deg,
            ];
        }

        $zone->update(array_merge([
            'name' => $data['name'] ?? null,
        ], $payload));

        return redirect()->route('zones.index', $zone->restaurant_id)
            ->with('success', 'Delivery zone updated successfully.');
    }

}
