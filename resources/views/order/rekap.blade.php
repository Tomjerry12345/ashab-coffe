@extends('layouts.app')

@section('title', 'Rekap Pesanan')

@section('content')
<div class="container-fluid px-4 py-4">

    <!-- Form Rekap -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('order.rekap') }}">
                <div class="mb-3">
                    <label class="form-label">Jenis Rekapan</label>
                    <select class="form-select" id="jenisRekap" name="jenis">
                        <option value="harian" {{ request('jenis') == 'harian' ? 'selected' : '' }}>Rekap Harian</option>
                        <option value="bulanan" {{ request('jenis') == 'bulanan' ? 'selected' : '' }}>Rekap Bulanan</option>
                        <option value="tanggal" {{ request('jenis') == 'tanggal' ? 'selected' : '' }}>Pilih Tanggal</option>
                    </select>
                </div>
                <div class="mb-3" id="tanggalWrapper" style="display: none;">
                    <label class="form-label">Pilih Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal', date('Y-m-d')) }}">
                </div>
                <button type="submit" class="btn btn-primary">Tampilkan</button>
            </form>
        </div>
    </div>

    <!-- Tabel Rekap -->
    <div class="card">
        <div class="card-body">
            <table id="rekapTable" class="display nowrap table table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Waktu Transaksi</th>
                        <th>Nomor Meja</th>
                        <th>Total Belanja</th>
                        <th>Jenis Pembayaran</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $i => $order)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $order->time ?? '-' }}</td>
                        <td>Meja Nomor {{ $order->meja_id ?? '-' }}</td>
                        <td>Rp. {{ number_format($order->total_belanja, 0, ',', '.') }}</td>
                        <td>{{ $order->payment_method ?? '-' }}</td>
                        <td>
                            @if($order->detail)
                            <ul>
                                @foreach(explode(',', $order->detail) as $item)
                                <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                            @else
                            Tidak ada item
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3 text-end">
                <h5>Total Semua Pesanan:
                    <span class="text-success">
                        Rp {{ number_format($orders->sum('total_belanja'), 0, ',', '.') }}
                    </span>
                </h5>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {

        $('#rekapTable').DataTable({
            dom: 'Bfrtip',
            buttons: ['excel', 'pdf', 'print']
        });

        // Fungsi untuk menampilkan input tanggal hanya jika jenis = 'tanggal'
        function toggleTanggalInput() {
            const jenis = $('#jenisRekap').val();

            if (jenis === 'tanggal') {
                $('#tanggalWrapper').show();
            } else {
                $('#tanggalWrapper').hide();
            }
        }

        // Jalankan saat halaman pertama kali dimuat
        toggleTanggalInput();

        // Jalankan setiap kali pilihan berubah
        $('#jenisRekap').on('change', toggleTanggalInput);
    });
</script>
@endpush