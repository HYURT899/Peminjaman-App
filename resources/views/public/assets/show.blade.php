@extends('layouts.app')

@section('title', 'Detail Asset')

@section('content')
    <div class="container mt-4">

        <div class="row">
            <!-- Gambar Asset -->
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white text-center">
                        <strong>Gambar Asset</strong>
                    </div>
                    <div class="card-body text-center">
                        @if ($asset->gambar)
                            <img src="{{ asset('storage/' . $asset->gambar) }}" alt="Gambar Asset"
                                class="img-fluid rounded mb-2" style="max-height: 250px; object-fit: cover;">
                        @else
                            <p class="text-muted">Tidak ada gambar</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Detail Informasi Asset -->
            <div class="col-md-5 mb-3">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <strong>Informasi Asset</strong>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th>Kode Asset</th>
                                <td>: {{ $asset->kode_asset }}</td>
                            </tr>
                            <tr>
                                <th>Nama Asset</th>
                                <td>: {{ $asset->nama_asset }}</td>
                            </tr>
                            <tr>
                                <th>Kategori</th>
                                <td>: {{ $asset->kategori->name }}</td>
                            </tr>
                            <tr>
                                <th>Keterangan</th>
                                <td>: {{ $asset->deskripsi ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- QR Code -->
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white text-center">
                        <strong>QR Code</strong>
                    </div>
                    <div class="card-body text-center">
                        @if ($asset->qr_code)
                            <img src="{{ asset('storage/' . $asset->qr_code) }}" alt="QR Code Asset" class="img-fluid mb-2"
                                style="max-height: 200px;">
                            <br>
                            <a href="{{ asset('storage/' . $asset->qr_code) }}"
                                download="qrcode-{{ $asset->kode_asset }}.png" class="btn btn-sm btn-primary">
                                <i class="fas fa-download"></i> Download
                            </a>
                        @else
                            <p class="text-muted">QR Code belum tersedia</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Kembali -->
        <div class="mt-3">
            <a href="{{ route('assets.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

    </div>
@stop
