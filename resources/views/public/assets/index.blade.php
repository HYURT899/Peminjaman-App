@extends('layouts.app')

@section('title', 'Daftar Asset')

@section('content')
    <div class="container py-4">
        {{-- Judul & filter --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="m-0">Daftar Asset</h2>

            <form method="GET" action="{{ route('assets.index') }}" class="d-flex align-items-center">
                <label for="category" class="fw-bold me-2 mb-0">Filter Kategori:</label>
                <select name="category" id="category" class="form-select" style="width: 200px;"
                    onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $selectedCategory == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        {{-- Pesan sukses --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Jika tidak ada asset --}}
        @if ($assets->isEmpty())
            <p class="text-muted">Belum ada aset.</p>
        @else
            <div class="row">
                @foreach ($assets as $asset)
                    <div class="col-6 col-md-3 mb-4">
                        <div class="card h-100 hover hover-5 rounded">
                            {{-- Gambar asset --}}
                            @if ($asset->gambar && file_exists(storage_path('app/public/' . $asset->gambar)))
                                <a href="{{ route('assets.show', $asset->id) }}">
                                    <img src="{{ asset('storage/' . $asset->gambar) }}" class="card-img-top"
                                        alt="{{ $asset->nama_asset }}">
                                </a>
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-light"
                                    style="height:200px;">
                                    <span class="text-muted">No image</span>
                                </div>
                            @endif

                            {{-- Detail asset --}}
                            <div class="card-body d-flex flex-column">
                                {{-- Nama asset --}}
                                <a class="card-title fw-semibold mb-1 link-underline link-underline-opacity-0"
                                    href="{{ route('assets.show', $asset->id) }}">
                                    {{ $asset->nama_asset }}
                                </a>

                                {{-- 🟢 Nama kategori --}}
                                @if ($asset->kategori)
                                    <span class="badge bg-secondary mb-2">{{ $asset->kategori->name }}</span>
                                @endif

                                {{-- Deskripsi --}}
                                @if ($asset->deskripsi)
                                    <p class="card-text text-muted">{{ Str::limit($asset->deskripsi, 80) }}</p>
                                @endif

                                {{-- Tombol tambah ke keranjang --}}
                                @auth
                                    @if (Auth::id() === 2)
                                        <div class="mt-auto">
                                            <form action="{{ route('keranjang.add') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="asset_id" value="{{ $asset->id }}">
                                                @if (auth()->user() && auth()->user()->hasRole('User'))
                                                    <button class="btn btn-primary">Tambah Keranjang</button>
                                                @endif
                                            </form>
                                        </div>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $assets->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection

@section('css')
    <style>
        #nama:hover {
            color: white;
            text-decoration: underline;
        }

        select.form-select {
            width: 200px;
            display: inline-block;
        }

        .badge {
            font-size: 0.75rem;
            background-color: #6c757d;
        }
    </style>
@stop
