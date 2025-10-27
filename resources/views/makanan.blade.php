@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid px-4 py-4">

    <!-- Tombol Tambah Makanan -->
    <div class="mb-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahMakanan">
            <i class="fas fa-plus"></i> Tambah Makanan
        </button>
    </div>

    <!-- Modal Tambah Makanan -->
    <div class="modal fade" id="modalTambahMakanan" tabindex="-1" aria-labelledby="modalTambahMakananLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahMakananLabel">Tambah Makanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <form id="formTambahMakanan" action="{{ route('makanan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="namaMakanan" class="form-label">Nama Makanan</label>
                            <input type="text" class="form-control @error('namaMakanan') is-invalid @enderror"
                                id="namaMakanan" name="namaMakanan"
                                placeholder="Nama Makanan" value="{{ old('namaMakanan') }}" required>
                            @error('namaMakanan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="hargaMakanan" class="form-label">Harga Makanan</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('hargaMakanan') is-invalid @enderror"
                                    id="hargaMakanan" name="hargaMakanan"
                                    placeholder="Harga Makanan" value="{{ old('hargaMakanan') }}" required>
                                @error('hargaMakanan')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="stokMakanan" class="form-label">Stok</label>
                            <input type="number" class="form-control @error('stokMakanan') is-invalid @enderror"
                                id="stokMakanan" name="stokMakanan"
                                value="{{ old('stokMakanan') }}" required>
                            @error('stokMakanan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="fotoMakanan" class="form-label">Foto Makanan</label>
                            <input type="file" class="form-control @error('fotoMakanan') is-invalid @enderror"
                                id="fotoMakanan" name="fotoMakanan" accept="image/*" required>
                            @error('fotoMakanan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror


                        </div>

                        <div class="mb-3">
                            <label for="model3D" class="form-label">Model 3D (opsional)</label>
                            <input type="file" class="form-control @error('model3D') is-invalid @enderror"
                                id="model3D" name="model3D" accept=".glb,.gltf">
                            @error('model3D')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    {{-- Script untuk auto show modal jika ada error --}}
    @if ($errors->any())
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var tambahModal = new bootstrap.Modal(document.getElementById('modalTambahMakanan'));
            tambahModal.show();
        });
    </script>
    @endif

    <!-- Daftar Makanan -->
    <div class="card">
        <!-- <div class="card-header">
            Daftar Makanan
        </div> -->
        <div class="card-body">

            @if (session('error'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Makanan</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Foto</th>
                        <th>Model 3D</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($makanans as $makanan)
                    <tr>
                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle">{{ $makanan->namaMakanan }}</td>
                        <td class="align-middle">Rp {{ number_format($makanan->hargaMakanan,0,',','.') }}</td>
                        <td class="text-center align-middle">{{ $makanan->stokMakanan }}</td>
                        <td class="text-center align-middle">
                            <img src="{{ asset('storage/'.$makanan->fotoMakanan) }}"
                                alt="{{ $makanan->namaMakanan }}" width="60" height="60" style="object-fit: cover;">
                        </td>
                        <td class="text-center align-middle" style="width: 120px;">
                            @if ($makanan->model3D)
                            <model-viewer src="{{ asset('storage/'.$makanan->model3D) }}"
                                alt="{{ $makanan->namaMakanan }}"
                                camera-controls auto-rotate
                                style="width: 60px; height: 60px; margin: auto; display: block;">
                            </model-viewer>
                            @else
                            <small>Tidak ada model</small>
                            @endif
                        </td>
                        <td class="text-center align-middle" style="width: 140px;">
                            <div class="d-flex justify-content-center gap-2">
                                <!-- Tombol Edit -->
                                <button class="btn btn-warning btn-sm w-100" style="min-width: 60px;" data-bs-toggle="modal"
                                    data-bs-target="#editModal{{ $makanan->id }}">
                                    Edit
                                </button>

                                <!-- Tombol Delete -->
                                <form action="{{ route('makanan.destroy', $makanan->id) }}" method="POST" style="width: 100%;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm w-100" style="min-width: 60px;"
                                        onclick="return confirm('Yakin ingin menghapus?')">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="editModal{{ $makanan->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('makanan.update', $makanan->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Makanan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">

                                        <div class="mb-3">
                                            <label for="namaMakanan" class="form-label">Nama Makanan</label>
                                            <input type="text" class="form-control" name="namaMakanan"
                                                value="{{ $makanan->namaMakanan }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="hargaMakanan" class="form-label">Harga Makanan</label>
                                            <input type="number" class="form-control" name="hargaMakanan"
                                                value="{{ $makanan->hargaMakanan }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="stokMakanan" class="form-label">Stok</label>
                                            <input type="number" class="form-control" name="stokMakanan"
                                                value="{{ $makanan->stokMakanan }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="fotoMakanan" class="form-label">Foto Makanan</label>
                                            <input type="file" class="form-control" name="fotoMakanan" accept="image/*">
                                            <small>Foto sekarang:</small><br>
                                            <img src="{{ asset('storage/'.$makanan->fotoMakanan) }}" width="80">
                                        </div>

                                        <div class="mb-3">
                                            <label for="model3D" class="form-label">Model 3D (opsional)</label>
                                            <input type="file" class="form-control @error('model3D') is-invalid @enderror"
                                                id="model3D" name="model3D" accept=".glb,.gltf">
                                            <small>Model 3D sekarang:</small><br>
                                            @if ($makanan->model3D)
                                            <model-viewer src="{{ asset('storage/'.$makanan->model3D) }}"
                                                camera-controls auto-rotate
                                                style="width: 60px; height: 60px; margin: auto; display: block;">
                                            </model-viewer>
                                            @else
                                            <small>Tidak ada model</small>
                                            @endif
                                            @error('model3D')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada data makanan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination kalau pakai paginate() --}}
            {{ $makanans->links('pagination::bootstrap-5') }}

        </div>
    </div>

</div>
@endsection