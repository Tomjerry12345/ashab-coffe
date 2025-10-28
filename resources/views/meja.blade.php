@extends('layouts.app')

@section('title', 'List Meja')

@section('content')
<div class="container-fluid px-2 py-2">

    <!-- Tombol Tambah Meja -->
    <div class="mb-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahMeja">
            <i class="fas fa-plus"></i> Tambah List Meja
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

    <!-- Daftar Meja -->
    <div class="row g-3">
        @foreach($mejas as $meja)
        <div class="col-md-3">
            <div class="card shadow-sm p-3 text-center">
                <img src="{{ $meja->qrCode }}" class="img-fluid mb-2" alt="QR Meja {{ $meja->nomorMeja }}">
                <p class="fw-semibold text-danger">Meja {{ $meja->nomorMeja }}</p>
                <div class="d-flex justify-content-around mt-2">
                    <a href="{{ $meja->qrCode }}" class="btn btn-sm btn-info text-white" download>
                        <i class="fas fa-download"></i>
                    </a>
                    <!-- <a href="{{ url("http://10.223.205.225:8000/order/meja/{$meja->nomorMeja}/minuman") }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-link"></i>
                    </a> -->
                    <a href="{{ url("/order/meja/{$meja->nomorMeja}/minuman") }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-link"></i>
                    </a>
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
@endsection