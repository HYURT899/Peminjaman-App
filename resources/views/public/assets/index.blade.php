@extends('layouts.app')

@section('title', 'Daftar Asset')

@section('content')
    <div class="container py-4">
        <h1 class="mb-4">Daftar Aset</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($assets->isEmpty())
            <p class="text-muted">Belum ada aset.</p>
        @else
            <div class="row">
                @foreach ($assets as $asset)
                    <div class="col-6 col-md-3 mb-4">
                        <div class="card h-100 hover hover-5 rounded">
                            @if ($asset->gambar)
                                <a href="{{ route('assets.show', $asset->id) }}">
                                    <img src="{{ asset('storage/' . $asset->gambar) }}" alt="Gambar Asset" class="card-img-top" data-full="{{ asset('storage/' . $asset->gambar) }}">
                                </a>
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-light"
                                    style="height:200px;">
                                    <span class="text-muted">No image</span>
                                </div>
                            @endif

                            <div class="card-body d-flex flex-column">
                                <a class="card-title link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover"
                                    href="{{ route('assets.show', $asset->id) }}">{{ $asset->nama_asset }}</a>
                                @if ($asset->deskripsi)
                                    <p class="card-text text-muted">{{ Str::limit($asset->deskripsi, 80) }}</p>
                                @endif
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
            text-decoration: underline
        }
    </style>
@stop
