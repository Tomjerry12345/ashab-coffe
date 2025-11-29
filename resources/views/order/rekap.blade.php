@extends('layouts.app')

@section('title', 'Rekap Pesanan')

@section('content')

<style>
    @media print {

        /* Ubah background dan font halaman print */
        body {
            background: #fff;
            font-family: "Poppins", Arial, sans-serif;
            padding: 20px;
        }

        /* Desain container invoice */
        .dt-print-view {
            border: 3px solid #1a73e8;
            border-radius: 10px;
            padding: 25px;
            width: 100%;
            position: relative;
        }

        /* Header INVOICE besar */
        .dt-print-view h1 {
            font-size: 32px;
            font-weight: 700;
            color: #1a73e8;
            text-align: center;
            border-bottom: 3px solid #1a73e8;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        /* Styling tabel */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        thead {
            font-size: 16px;
            font-weight: 600;
            border-bottom: 2px solid #1a73e8;
            background: #1a73e8 !important;
            color: #fff !important;
            text-transform: uppercase;
        }

        th,
        td {
            border: 1px solid #1a73e8;
            padding: 10px;
            font-size: 15px;
            text-align: left;
        }

        /* Footer total besar */
        .dt-print-view .total-footer {
            margin-top: 25px;
            font-size: 22px;
            font-weight: bold;
            text-align: right;
            color: #1a73e8;
        }

        /* Dekorasi garis lengkung (optional biar mirip invoice kamu) */
        .dt-print-view::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, #1a73e8, #fbbc05);
        }

        .dt-print-view::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, #fbbc05, #1a73e8);
        }
    }
</style>

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
            buttons: [
                'excel',
                {
                    extend: 'pdf',
                    title: '',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    title: '',
                    customize: function(win) {
                        $(win.document.body)
                            .css('font-family', 'Poppins, Arial')
                            .css('padding', '20px')
                            .prepend(`
                            <div class="dt-print-view">
                                <h1>INVOICE</h1>
                                <p><strong>Tanggal cetak:</strong> ${new Date().toLocaleDateString()}</p>
                                <p><strong>Jenis rekapan:</strong> {{ request('jenis') }}</p>
                            </div>
                        `);

                        // Tambah total di bawah tabel
                        $(win.document.body).append(`
                        <div class="total-footer">
                            Total Semua Pesanan: Rp {{ number_format($orders->sum('total_belanja'),0,',','.') }}
                        </div>
                    `);
                    }
                }
            ]
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