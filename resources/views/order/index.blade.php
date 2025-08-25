@extends('layouts.master')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Orders</h3>
        <a href="{{ route('orders.create') }}" class="btn btn-success">+ Create Order</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Restaurant</th>
                    <th>Delivery Man</th>
                    <th>Address</th>
                    <th>Lat, Lng</th>
                    <th>Status</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->user->name ?? 'Not Available' }}</td>
                    <td>{{ $order->restaurant->name ?? 'Not Available' }}</td>
                    <td>{{ $order->deliveryMan->name ?? 'Not Available' }}</td>
                    <td>{{ $order->address ?? 'Not Available' }}</td>
                    <td>
                        @if($order->lat && $order->lng)
                            {{ $order->lat }}, {{ $order->lng }}
                        @else
                            Not Available
                        @endif
                    </td>
                    <td>
                        @php
                            $statusClass = [
                                'pending' => 'bg-warning',
                                'assigned' => 'bg-primary',
                                'completed' => 'bg-success',
                                'rejected' => 'bg-danger'
                            ];
                        @endphp
                        <span class="badge {{ $statusClass[$order->status] ?? 'bg-secondary' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td>{{ $order->created_at ? $order->created_at->format('d M Y, h:i A') : 'Not Available' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted">No orders available</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
