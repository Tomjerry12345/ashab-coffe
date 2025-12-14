@extends('layouts.app')

@section('title', 'Pesanan baru')

@section('content')
<div class="content">
    <h3>Pesanan Baru</h3>
    <div class="card p-3">
        <table id="pesananTable" class="table table-striped">
            <thead>
                <tr>
                    <th>Antrian</th>
                    <th>Nomor Meja</th>
                    <th>Total Belanja</th>
                    <th>Jenis Pembayaran</th>
                    <th>Makanan</th>
                    <th>Minuman</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $i => $order)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>Meja Nomor {{ $order->meja_id }}</td>
                    <td>Rp. {{ number_format($order->total,0,',','.') }}</td>
                    <td>{{ ucfirst($order->payment_method) }}</td>

                    {{-- 🍽 KOLOM MAKANAN --}}
                    <td>
                        @forelse($order->makanan as $item)
                        • {{ $item->nama }} (x{{ $item->jumlah }})<br>
                        @empty
                        <em>-</em>
                        @endforelse
                    </td>

                    {{-- 🥤 KOLOM MINUMAN --}}
                    <td>
                        @forelse($order->minuman as $item)
                        • {{ $item->nama }} (x{{ $item->jumlah }})<br>
                        @empty
                        <em>-</em>
                        @endforelse
                    </td>

                    <td>
                        <span class="status">
                            {{ strtoupper($order->status) }}
                        </span>
                    </td>

                    <td>
                        <div class="d-flex flex-column gap-2">
                            <form action="{{ route('order.updateStatus', $order->order_key) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm w-100">
                                    @if($order->status === 'pending')
                                    ✔ Konfirmasi (Cooking)
                                    @elseif($order->status === 'cooking')
                                    ✔ Selesai (Finished)
                                    @else
                                    ✔ Selesai
                                    @endif
                                </button>
                            </form>

                            @if($order->status === 'pending')
                            <form action="{{ route('order.batal', $order->order_key) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm w-100">
                                    ✘ Batal
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#pesananTable').DataTable();
    });
</script>
@endpush