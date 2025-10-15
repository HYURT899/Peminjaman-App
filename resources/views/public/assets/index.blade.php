@extends('layouts.app')

@section('title', 'Daftar Asset')

@section('content')
    <div class="container py-4">

        {{-- Header: judul & filter sejajar --}}
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <h2 class="m-0 fw-bold">Daftar Asset</h2>

            <form method="GET" action="{{ route('assets.index') }}" class="d-flex align-items-center">
                <label for="category" class="fw-semibold me-2 mb-0">Filter:</label>
                <select name="category" id="category" class="form-select shadow-sm border-primary"
                    style="width: 200px; border-radius: 8px;" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $selectedCategory == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        {{-- Notifikasi sukses --}}
        @if (session('success'))
            <div class="alert alert-success shadow-sm rounded-3">
                {{ session('success') }}
            </div>
        @endif

        {{-- Jika tidak ada asset --}}
        @if ($assets->isEmpty())
            <p class="text-muted text-center">Belum ada aset yang tersedia.</p>
        @else
            <div class="row">
                @foreach ($assets as $asset)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="card h-100 border-0 shadow-lg rounded-4 hover-card transition-all">
                            {{-- Gambar --}}
                            @if ($asset->gambar && file_exists(storage_path('app/public/' . $asset->gambar)))
                                <a href="{{ route('assets.show', $asset->id) }}">
                                    <img src="{{ asset('storage/' . $asset->gambar) }}" class="card-img-top rounded-top-4"
                                        alt="{{ $asset->nama_asset }}" style="height: 180px; object-fit: cover;">
                                </a>
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-light rounded-top-4"
                                    style="height:180px;">
                                    <span class="text-muted">No image</span>
                                </div>
                            @endif

                            {{-- Body --}}
                            <div class="card-body d-flex flex-column">
                                {{-- Nama asset --}}
                                <a href="{{ route('assets.show', $asset->id) }}"
                                    class="card-title fw-semibold text-dark mb-1 text-decoration-none hover-text-primary">
                                    {{ $asset->nama_asset }}
                                </a>

                                {{-- 🟢 Kategori teks biasa tapi sedikit tebal --}}
                                @if ($asset->kategori)
                                    <p class="text-secondary fw-semibold small mb-2">
                                        {{ $asset->kategori->name }}
                                    </p>
                                @endif

                                {{-- Deskripsi --}}
                                @if ($asset->deskripsi)
                                    <p class="card-text text-muted small mb-3">
                                        {{ Str::limit($asset->deskripsi, 80) }}
                                    </p>
                                @endif

                                {{-- Tombol --}}
                                @if (Auth::id() === 2)
                                    <div class="mt-auto">
                                        <form action="{{ route('keranjang.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="asset_id" value="{{ $asset->id }}">
                                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                                <i class="fas fa-cart-plus pl-3"></i>
                                                Tambah ke Peminjaman
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $assets->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection

@section('css')
    <style>
        /* Efek hover lembut pada card */
        .hover-card {
            transition: all 0.3s ease-in-out;
            background-color: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .hover-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 14px 32px rgba(0, 0, 0, 0.20);
        }

        /* Hover teks */
        .hover-text-primary:hover {
            color: #0d6efd !important;
        }

        /* Kategori text */
        .text-secondary {
            color: #6c757d !important;
            letter-spacing: 0.3px;
        }
    </style>
@stop
@section('js')
    <script>
        // Inisialisasi tooltips Bootstrap
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
@stop
