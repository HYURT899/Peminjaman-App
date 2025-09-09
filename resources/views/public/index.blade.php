@extends('layouts.app')

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
                    <div class="col-md-3 mb-4">
                        <div class="card h-100">
                            @if ($asset->gambar && file_exists(public_path($asset->gambar)))
                                <img src="{{ asset($asset->gambar) }}" class="card-img-top" alt="{{ $asset->nama_asset }}">
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-light" style="height:200px;">
                                    <span class="text-muted">No image</span>
                                </div>
                            @endif

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $asset->nama_asset }}</h5>
                                @if ($asset->deskripsi)
                                    <p class="card-text text-muted">{{ Str::limit($asset->deskripsi, 80) }}</p>
                                @endif

                                <div class="mt-auto">
                                    <form action="{{ route('peminjam.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="asset_id" value="{{ $asset->id }}">
                                        <button type="button" class="btn btn-primary btn-sm w-100" data-bs-toggle="modal"
                                            data-bs-target="#modalPeminjaman" data-barang-id="{{ $asset->id }}"
                                            data-barang-nama="{{ $asset->nama_asset }}">
                                            Pinjam
                                        </button>
                                        @include('layouts.modalMinjam')
                                    </form>
                                </div>
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
