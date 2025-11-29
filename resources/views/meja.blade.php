@extends('layouts.app')

@section('title', 'List Meja')

@section('content')
<div class="container-fluid px-2 py-2">

    <!-- Tombol Tambah Meja -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahMeja">
            <i class="fas fa-plus"></i> Tambah List Meja
        </button>

        <button class="btn btn-success ms-auto" onclick="downloadStatusQR()">
            <i class="fas fa-qrcode"></i> Download QR Status Meja
        </button>
    </div>

    <!-- Modal Tambah Meja -->
    <div class="modal fade" id="modalTambahMeja" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('meja.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Meja</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nomor Meja</label>
                            <input type="text" class="form-control" name="nomorMeja" placeholder="Contoh 01" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @php
    use Carbon\Carbon;

    $batasMenit = 60;
    $warning = [];
    $almost = [];
    $errorHuman = [];

    foreach ($mejas as $m) {
    if ($m->status === 'terisi' && $m->terisi_sejak) {

    // hitung pakai UTC biar akurat
    $terisi = $m->terisi_sejak->setTimezone('UTC'); // pastikan basis hitung UTC
    $sekarang = Carbon::now('Asia/Makassar');

    $lamaMenit = $sekarang->diffInMinutes($terisi, true);

    // baru convert ke Asia/Makassar buat tampilan
    $formatWaktu=$terisi->setTimezone('Asia/Makassar')->format('H:i:s');

    $formatMenit = round($lamaMenit);

    if ($lamaMenit >= $batasMenit) {
    $warning[] = "Meja {$m->nomorMeja} terisi sejak {$formatWaktu} — sudah {$formatMenit} menit berlalu";
    } elseif ($lamaMenit >= ($batasMenit - 0.2)) {
    $almost[] = "Meja {$m->nomorMeja} hampir lewat batas ({$formatMenit} menit)";
    }
    }

    if ($m->status === 'terisi' && !$m->terisi_sejak) {
    $errorHuman[] = "Meja {$m->nomorMeja} terisi tapi tidak punya timestamp!";
    }
    }
    @endphp

    @if(count($errorHuman))
    <div class="alert alert-warning">
        ⚠ <strong>Peringatan Data:</strong>
        <ul>
            @foreach($errorHuman as $e)
            <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(count($warning))
    <div class="alert alert-danger">
        ⏰ <strong>Meja Melewati Batas Waktu:</strong>
        <ul>
            @foreach($warning as $w)
            <li>{{ $w }}</li>
            @endforeach
        </ul>
        <small class="text-muted">Waktu sekarang: {{ \Carbon\Carbon::now('Asia/Makassar')->format('d/m/Y H:i:s') }}</small>
    </div>
    @endif

    @if(count($almost))
    <div class="alert alert-info">
        ⌛ <strong>Meja Hampir Melewati Batas:</strong>
        <ul>
            @foreach($almost as $a)
            <li>{{ $a }}</li>
            @endforeach
        </ul>
    </div>
    @endif


    <!-- Daftar Meja -->
    <div class="row g-3">
        @foreach($mejas as $meja)
        <div class="col-md-3">
            <div class="card shadow-sm p-3 text-center">
                <img src="{{ $meja->qrCode }}" class="img-fluid mb-2" alt="QR Meja {{ $meja->nomorMeja }}">
                <p class="fw-semibold {{ $meja->status == 'terisi' ? 'text-danger' : 'text-success' }}">
                    Meja {{ $meja->nomorMeja }} — {{ ucfirst($meja->status) }}
                </p>
                <div class="d-flex justify-content-around mt-2">
                    <a href="{{ $meja->qrCode }}" class="btn btn-sm btn-info text-white" download>
                        <i class="fas fa-download"></i>
                    </a>
                    <a href="{{ url("/order/meja/{$meja->nomorMeja}/minuman") }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-link"></i>
                    </a>
                    <form action="{{ route('meja.toggle', $meja->id) }}" method="POST">
                        @csrf
                        <button
                            class="btn btn-sm {{ $meja->status == 'terisi' ? 'btn-warning' : 'btn-success' }}"
                            title="{{ $meja->status == 'terisi' ? 'Ubah ke Kosong (Meja sedang terisi)' : 'Tandai Terisi (Meja masih kosong)' }}">
                            <i class="fas {{ $meja->status == 'terisi' ? 'fa-chair' : 'fa-user-plus' }}"></i>
                        </button>
                    </form>
                    <form action="{{ route('meja.destroy', $meja->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>

<script>
    function downloadStatusQR() {
        window.location.href = "{{ route('meja.status.qr') }}";
    }
</script>
@endsection