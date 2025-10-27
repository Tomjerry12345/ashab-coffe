@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row g-4">

    <!-- Pesanan Selesai -->
    <div class="col-md-3">
        <div class="card text-white bg-primary h-100">
            <div class="card-body">
                <h5 class="card-title">Pesanan Selesai</h5>
                <h2>{{ $totalOrders }}</h2>
                <p>Pesanan dengan status "finished"</p>
            </div>
        </div>
    </div>

    <!-- Total Pendapatan -->
    <div class="col-md-3">
        <div class="card text-white bg-success h-100">
            <div class="card-body">
                <h5 class="card-title">Total Pendapatan</h5>
                <h2>Rp {{ number_format($revenue, 0, ',', '.') }}</h2>
                <p>Dari semua pesanan selesai</p>
            </div>
        </div>
    </div>

    <!-- Total Meja / Pelanggan -->
    <div class="col-md-3">
        <div class="card text-white bg-warning h-100">
            <div class="card-body">
                <h5 class="card-title">Total Meja Aktif</h5>
                <h2>{{ $totalUsers }}</h2>
                <p>Meja dengan pesanan selesai</p>
            </div>
        </div>
    </div>

    <!-- Contoh Tambahan (optional) -->
    <div class="col-md-3">
        <div class="card text-white bg-danger h-100">
            <div class="card-body">
                <h5 class="card-title">Pesanan Aktif</h5>
                <h2>{{ \App\Models\Order::whereNotIn('status', ['finished','cancelled'])->count() }}</h2>
                <p>Masih dalam proses</p>
            </div>
        </div>
    </div>

</div>
@endsection