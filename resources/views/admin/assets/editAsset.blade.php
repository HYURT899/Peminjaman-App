@extends('adminlte::page')

@section('title', 'Edit Asset')

@section('content_header')
    <h1 class="text-xl text-bold">Edit Asset</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.assets.update', $asset->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kode_asset">Kode Asset</label>
                                    <input type="text" name="kode_asset" id="kode_asset"
                                        class="form-control @error('kode_asset') is-invalid @enderror"
                                        value="{{ old('kode_asset', $asset->kode_asset) }}" required>
                                    @error('kode_asset')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_asset">Nama Asset</label>
                                    <input type="text" name="nama_asset" id="nama_asset"
                                        class="form-control @error('nama_asset') is-invalid @enderror"
                                        value="{{ old('nama_asset', $asset->nama_asset) }}" required>
                                    @error('nama_asset')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="gambar">Gambar</label>
                            <div class="custom-file">
                                <input type="file" name="gambar" id="gambar"
                                    class="custom-file-input @error('gambar') is-invalid @enderror">
                                <label class="custom-file-label" for="gambar">Pilih file gambar baru (biarkan kosong jika
                                    tidak ingin mengubah)</label>
                            </div>
                            @error('gambar')
                                <span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            @if ($asset->gambar)
                                <div class="mt-2">
                                    <label>Gambar Saat Ini:</label>
                                    <div>
                                        <img src="{{ asset('storage/' . $asset->gambar) }}" alt="Gambar Asset"
                                            class="img-thumbnail" style="max-height: 200px;">
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="3">{{ old('deskripsi', $asset->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="category_id">Kategori</label>
                            <select name="category_id" id="category_id"
                                class="form-control @error('category_id') is-invalid @enderror" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $asset->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Perbarui
                            </button>
                            <a href="{{ route('admin.assets.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .card {
            border: 1px solid #d2d6de;
            border-radius: 3px;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .btn {
            border-radius: 3px;
            padding: 8px 16px;
        }

        .img-thumbnail {
            padding: 0.25rem;
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            max-width: 100%;
            height: auto;
        }
    </style>
@stop

@section('js')
    <script>
        // Menampilkan nama file yang dipilih di input file
        document.querySelector('.custom-file-input').addEventListener('change', function(e) {
            var fileName = document.getElementById("gambar").files[0].name;
            var nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
        });
    </script>
@stop
