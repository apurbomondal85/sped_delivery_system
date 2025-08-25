<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\DeliveryZone;
use Illuminate\Http\Request;
use App\Notifications\OrderAssigned;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = auth()->user()->restaurant->orders()->orderBy('created_at', 'desc')->get();

        return view('order.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = User::where('type', 'customer')->get();

        return view('order.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'restaurant_id' => 'required|exists:restaurants,id',
            'address' => 'required|string|max:255',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $lat = $request->lat;
        $lng = $request->lng;
        $restaurant_id = $request->restaurant_id;

        $zones = DeliveryZone::where('restaurant_id', $restaurant_id)->get();

        $insideZone = false;

        foreach ($zones as $zone) {
            if ($zone->type === 'radius') {
                $distance = $this->distance($lat, $lng, $zone->center_lat, $zone->center_lng);
                if ($distance <= $zone->radius_meters) {
                    $insideZone = true;
                    break;
                }
            } elseif ($zone->type === 'polygon') {
                if ($this->pointInPolygon([$lat, $lng], $zone->coordinates)) {
                    $insideZone = true;
                    break;
                }
            }
        }

        if (!$insideZone) {
            return back()->withErrors(['address' => 'Delivery address is outside restaurant delivery zones'])->withInput();
        }

        $deliveryMan = User::where('type', 'delivery_man')
            ->whereNotIn('id', Order::where('status', 'assigned')->pluck('delivery_man_id'))
            ->get()
            ->sortBy(function ($d) use ($lat, $lng) {
                return $this->distance($lat, $lng, $d->lat, $d->lng);
            })
            ->first();

        $assignedDeliveryManId = $deliveryMan ? $deliveryMan->id : null;

        $order = Order::create([
            'user_id' => $request->user_id,
            'restaurant_id' => $restaurant_id,
            'address' => $request->address,
            'lat' => $lat,
            'lng' => $lng,
            'delivery_man_id' => $assignedDeliveryManId,
        ]);

        if ($assignedDeliveryManId) {
            $deliveryMan->notify(new OrderAssigned($order));
        }

        return redirect()->route('orders.index')->with('success', 'Order created successfully');
    }

    private function distance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLng / 2) * sin($dLng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }

    private function pointInPolygon($point, $polygon)
    {
        $x = $point[0];
        $y = $point[1];
        $inside = false;
        $j = count($polygon) - 1;

        for ($i = 0; $i < count($polygon); $i++) {
            $xi = $polygon[$i][0];
            $yi = $polygon[$i][1];
            $xj = $polygon[$j][0];
            $yj = $polygon[$j][1];

            $intersect = (($yi > $y) != ($yj > $y)) &&
                ($x < ($xj - $xi) * ($y - $yi) / ($yj - $yi + 0.0000001) + $xi);
            if ($intersect) $inside = !$inside;
            $j = $i;
        }

        return $inside;
    }

    public function deliveryMan()
    {
        $orders = Order::where('status', 'pending')->get();

        return view('deliveryMan.index', compact('orders'));
    }

    public function accept(Order $order)
    {
        $order->update(['status' => 'assigned']);
        return back()->with('success', 'Order accepted successfully.');
    }

    public function reject(Order $order)
    {
        $lat = $order->lat;
        $lng = $order->lng;

        $availableDeliveryMen = User::where('type', 'delivery_man')
            ->whereNotIn('id', Order::where('status', 'assigned')->pluck('delivery_man_id'))
            ->whereNot('id', $order->delivery_man_id)
            ->get();

        if ($availableDeliveryMen->isNotEmpty()) {
            $nearestDeliveryMan = $availableDeliveryMen->sortBy(function ($d) use ($lat, $lng) {
                return $this->distance($lat, $lng, $d->lat, $d->lng);
            })->first();

            $order->update([
                'delivery_man_id' => $nearestDeliveryMan->id,
            ]);

            $nearestDeliveryMan->notify(new OrderAssigned($order));

            return back()->with('success', 'Order reassigned to another delivery man.');
        }

        return back()->with('error', 'No available delivery man found, order is still pending.');
    }
}
