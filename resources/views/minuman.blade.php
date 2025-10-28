@extends('layouts.app')

@section('title', 'Dashboard - Minuman')

@section('content')
<div class="container-fluid px-4 py-4">

    <!-- Tombol Tambah Minuman -->
    <div class="mb-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahMinuman">
            <i class="fas fa-plus"></i> Tambah Minuman
        </button>
    </div>

    <!-- Modal Tambah Minuman -->
    <div class="modal fade" id="modalTambahMinuman" tabindex="-1" aria-labelledby="modalTambahMinumanLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahMinumanLabel">Tambah Minuman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('minuman.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="namaMinuman" class="form-label">Nama Minuman</label>
                            <input type="text" class="form-control @error('namaMinuman') is-invalid @enderror"
                                id="namaMinuman" name="namaMinuman" placeholder="Nama Minuman"
                                value="{{ old('namaMinuman') }}" required>
                            @error('namaMinuman')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="hargaMinuman" class="form-label">Harga Minuman</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('hargaMinuman') is-invalid @enderror"
                                    id="hargaMinuman" name="hargaMinuman" placeholder="Harga Minuman"
                                    value="{{ old('hargaMinuman') }}" required>
                                @error('hargaMinuman')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="stokMinuman" class="form-label">Stok</label>
                            <input type="number" class="form-control @error('stokMinuman') is-invalid @enderror"
                                id="stokMinuman" name="stokMinuman" value="{{ old('stokMinuman') }}" required>
                            @error('stokMinuman')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="fotoMinuman" class="form-label">Foto Minuman</label>
                            <input type="file" class="form-control @error('fotoMinuman') is-invalid @enderror"
                                id="fotoMinuman" name="fotoMinuman" accept="image/*" required>
                            @error('fotoMinuman')
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
            var tambahModal = new bootstrap.Modal(document.getElementById('modalTambahMinuman'));
            tambahModal.show();
        });
    </script>
    @endif

    <!-- Daftar Minuman -->
    <div class="card">
        <div class="card-header">Daftar Minuman</div>
        <div class="card-body">

            @if (session('success'))
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
                        <th>Nama Minuman</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Foto</th>
                        <th>Model 3D</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($minumans as $minuman)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $minuman->namaMinuman }}</td>
                        <td>Rp {{ number_format($minuman->hargaMinuman, 0, ',', '.') }}</td>
                        <td>{{ $minuman->stokMinuman }}</td>
                        <td>
                            <img src="{{ $minuman->fotoMinuman }}" alt="{{ $minuman->namaMinuman }}" width="60">
                        </td>
                        <td class="text-center align-middle" style="width: 120px;">
                            @if ($minuman->model3D)
                            <model-viewer src="{{ $minuman->model3D }}"
                                alt="{{ $minuman->namaMinuman }}"
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
                                    data-bs-target="#editModal{{ $minuman->id }}">
                                    Edit
                                </button>

                                <!-- Tombol Delete -->
                                <form action="{{ route('minuman.destroy', $minuman->id) }}" method="POST" style="width: 100%;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm w-100" style="min-width: 60px;"
                                        onclick="return confirm('Yakin ingin menghapus?')">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                        <!-- <td> -->
                        <!-- Tombol Edit -->
                        <!-- <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $minuman->id }}">
                                Edit
                            </button> -->

                        <!-- Tombol Delete -->
                        <!-- <form action="{{ route('minuman.destroy', $minuman->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">
                                    Hapus
                                </button>
                            </form> -->
                        <!-- </td> -->
                    </tr>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="editModal{{ $minuman->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('minuman.update', $minuman->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Minuman</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">

                                        <div class="mb-3">
                                            <label for="namaMinuman" class="form-label">Nama Minuman</label>
                                            <input type="text" class="form-control" name="namaMinuman" value="{{ $minuman->namaMinuman }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="hargaMinuman" class="form-label">Harga Minuman</label>
                                            <input type="number" class="form-control" name="hargaMinuman" value="{{ $minuman->hargaMinuman }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="stokMinuman" class="form-label">Stok</label>
                                            <input type="number" class="form-control" name="stokMinuman" value="{{ $minuman->stokMinuman }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="fotoMinuman" class="form-label">Foto Minuman</label>
                                            <input type="file" class="form-control" name="fotoMinuman" accept="image/*">
                                            <small>Foto sekarang:</small><br>
                                            <img src="{{ $minuman->fotoMinuman }}" width="80">
                                        </div>

                                        <div class="mb-3">
                                            <label for="model3D" class="form-label">Model 3D (opsional)</label>
                                            <input type="file" class="form-control @error('model3D') is-invalid @enderror"
                                                id="model3D" name="model3D" accept=".glb,.gltf">

                                            <small>Model 3D sekarang:</small><br>
                                            @if ($minuman->model3D)
                                            <model-viewer src="{{ $minuman->model3D }}"
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
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada data minuman.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination kalau pakai paginate() --}}
            {{ $minumans->links('pagination::bootstrap-5') }}

        </div>
    </div>

</div>
@endsection