@extends('layouts.master')

@section('content')
    <div class="container py-4">
        <h3>Assigned Orders</h3>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if ($orders->isEmpty())
            <div class="alert alert-warning">No assigned orders at the moment.</div>
        @else
            <div class="row g-4 mt-3">
                @foreach ($orders as $order)
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <strong>Order #{{ $order->id }}</strong>
                                <span class="float-end">{{ ucfirst($order->status) }}</span>
                            </div>
                            <div class="card-body">
                                <p><strong>Customer:</strong> {{ $order->user->name ?? '-' }}</p>
                                <p><strong>Restaurant:</strong> {{ $order->restaurant->name ?? '-' }}</p>
                                <p><strong>Address:</strong> {{ $order->address }}</p>

                                <form action="{{ route('orders.accept', $order) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-success btn-sm">Accept</button>
                                </form>

                                <form action="{{ route('orders.reject', $order) }}" method="POST"
                                    class="d-inline ms-2">
                                    @csrf
                                    <button class="btn btn-danger btn-sm">Reject</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
