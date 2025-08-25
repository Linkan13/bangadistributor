@extends('backEnd.master')

@section('mainContent')
<div class="container mt-4">
    <h4>Orders for Distributor: {{ $distributor->first_name }} {{ $distributor->last_name }}</h4>
    <p>Email: {{ $distributor->email }}</p>

    <table class="table table-bordered mt-3">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Order ID</th>
                <th>Percentage</th>
                <th>Payment Status</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $index => $order)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $order->order_id }}</td>
                    <td>{{ $order->percentage }}%</td>
                    <td>{{ ucfirst($order->payment_status) }}</td>
                    <td>{{ $order->created_at }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">No orders found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
