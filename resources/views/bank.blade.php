@extends('layouts.app')

@section('title', 'List Bank')

@section('content')
<div class="container-fluid px-2 py-2">

    <!-- Tombol Tambah Bank -->
    <div class="mb-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahBank">
            <i class="fas fa-plus"></i> Tambah Bank QRIS
        </button>
    </div>

    <!-- Modal Tambah Bank -->
    <div class="modal fade" id="modalTambahBank" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('bank.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Bank</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Bank / E-Wallet</label>
                            <input type="text" class="form-control" name="nama_bank" placeholder="Contoh: BCA, DANA, OVO" required>
                        </div>
                        <!-- <div class="mb-3">
                            <label class="form-label">Nomor Rekening / ID QRIS (Opsional)</label>
                            <input type="text" class="form-control" name="nomorRekening" placeholder="Opsional">
                        </div> -->
                        <div class="mb-3">
                            <label class="form-label">Upload Gambar QRIS</label>
                            <input type="file" class="form-control" name="gambar_qris" accept="image/*" required>
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

    <!-- Daftar Bank -->
    <div class="row g-3">
        @foreach($banks as $bank)
        <div class="col-md-3">
            <div class="card shadow-sm p-3 text-center">
                <img src="{{ asset('storage/' . $bank->gambar_qris) }}" class="img-fluid mb-2" alt="QRIS {{ $bank->nama_bank }}">
                <p class="fw-semibold text-danger">{{ $bank->nama_bank }}</p>
                <div class="d-flex justify-content-around mt-2">
                    <a href="{{ asset('storage/' . $bank->gambar_qris) }}" class="btn btn-sm btn-info text-white" download>
                        <i class="fas fa-download"></i>
                    </a>
                    <form action="{{ route('bank.destroy', $bank->id) }}" method="POST">
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
@endsection