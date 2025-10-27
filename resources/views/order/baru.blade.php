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
                    <th>Detail</th>
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
                    <td>
                        @foreach($order->items as $item)
                        • {{ $item->nama }} (x{{ $item->jumlah }}) <br>
                        @endforeach
                    </td>
                    <td>
                        <span class="status">
                            {{ strtoupper($order->status) }}
                        </span>
                    </td>
                    <td>
                        <form action="{{ route('order.updateStatus', $order->order_key) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm">
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
                        <form action="{{ route('order.batal', $order->order_key) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">✘ Batal</button>
                        </form>
                        @endif
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