@extends('adminlte::page')

@section('title', 'Detail Peminjaman')

@section('content_header')
    <h1 class="text-xl text-bold">Detail Peminjaman</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Peminjaman</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.peminjam.edit', $peminjaman->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.peminjam.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Kode Peminjaman</th>
                                    <td>#{{ $peminjaman->id }}</td>
                                </tr>
                                <tr>
                                    <th>Peminjam</th>
                                    <td>{{ $peminjaman->nama_peminjam }}</td>
                                </tr>
                                <tr>
                                    <th>Asset yang Dipinjam</th>
                                    <td>
                                        {{ $peminjaman->asset->kode_asset }} - {{ $peminjaman->asset->nama_asset }}
                                        @if($peminjaman->asset->gambar)
                                            <br>
                                            <img src="{{ asset('storage/' . $peminjaman->asset->gambar) }}" alt="Gambar Asset" class="img-thumbnail mt-2" style="max-height: 150px;">
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Jumlah</th>
                                    <td>{{ $peminjaman->jumlah }}</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Tanggal Pinjam</th>
                                    <td>{{ $peminjaman->tanggal_pinjam }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge 
                                            @if($peminjaman->status == 'menunggu') badge-warning
                                            @elseif($peminjaman->status == 'disetujui') badge-success
                                            @elseif($peminjaman->status == 'ditolak') badge-danger
                                            @elseif($peminjaman->status == 'dikembalikan') badge-info
                                            @endif">
                                            {{ ucfirst($peminjaman->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Keperluan</th>
                                    <td>{{ $peminjaman->keperluan }}</td>
                                </tr>
                                <tr>
                                    <th>Catatan</th>
                                    <td>{{ $peminjaman->catatan ?: '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Informasi Approval -->
                    @if($peminjaman->disetujui_oleh && $peminjaman->disetujui_pada)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <h5><i class="fas fa-check-circle"></i> Informasi Approval</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Disetujui oleh:</strong> {{ $peminjaman->disetujuiOleh->name ?? 'Admin' }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Tanggal Approval:</strong> {{ $peminjaman->disetujui_pada }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Timeline Status -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5><i class="fas fa-history"></i> Timeline</h5>
                            <ul class="timeline">
                                <li>
                                    <i class="fas fa-plus bg-blue"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> {{ $peminjaman->created_at }}</span>
                                        <h3 class="timeline-header">Peminjaman Diajukan</h3>
                                        <div class="timeline-body">
                                            Peminjaman dibuat oleh sistem
                                        </div>
                                    </div>
                                </li>
                                
                                @if($peminjaman->disetujui_pada)
                                <li>
                                    <i class="fas fa-check bg-green"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> {{ $peminjaman->disetujui_pada }}</span>
                                        <h3 class="timeline-header">Peminjaman Disetujui</h3>
                                        <div class="timeline-body">
                                            Disetujui oleh {{ $peminjaman->disetujuiOleh->name ?? 'Admin' }}
                                        </div>
                                    </div>
                                </li>
                                @endif
                                
                                <li>
                                    <i class="fas fa-clock bg-gray"></i>
                                </li>
                            </ul>
                        </div>
                    </div>
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

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #d2d6de;
            padding: 15px 20px;
        }

        .card-body {
            padding: 20px;
        }

        .table th {
            background-color: #f8f9fa;
        }

        .badge {
            padding: 0.5em 0.8em;
            font-size: 0.9em;
        }

        /* Timeline CSS */
        .timeline {
            position: relative;
            margin: 0 0 30px 0;
            padding: 0;
            list-style: none;
        }

        .timeline:before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #ddd;
            left: 31px;
            margin: 0;
            border-radius: 2px;
        }

        .timeline > li {
            position: relative;
            margin-right: 10px;
            margin-bottom: 15px;
        }

        .timeline > li:before,
        .timeline > li:after {
            content: " ";
            display: table;
        }

        .timeline > li:after {
            clear: both;
        }

        .timeline > li > .timeline-item {
            margin-left: 60px;
            margin-right: 15px;
            margin-top: 0;
            background: #fff;
            color: #444;
            padding: 10px;
            position: relative;
            border-radius: 3px;
            border: 1px solid #ddd;
        }

        .timeline > li > .fa {
            width: 30px;
            height: 30px;
            font-size: 15px;
            line-height: 30px;
            position: absolute;
            color: #fff;
            background: #d2d6de;
            border-radius: 50%;
            text-align: center;
            left: 18px;
            top: 0;
        }

        .timeline > li .timeline-header {
            margin: 0;
            color: #555;
            border-bottom: 1px solid #f4f4f4;
            padding: 5px;
            font-size: 16px;
            line-height: 1.1;
        }

        .timeline > li .timeline-body,
        .timeline > li .timeline-footer {
            padding: 10px;
        }

        .timeline > li .time {
            float: right;
            color: #999;
            font-size: 12px;
        }

        .timeline > li .bg-blue {
            background-color: #007bff !important;
        }

        .timeline > li .bg-green {
            background-color: #28a745 !important;
        }

        .timeline > li .bg-gray {
            background-color: #6c757d !important;
        }
    </style>
@stop

@section('js')
    <script>
        console.log('Halaman detail peminjaman loaded');
    </script>
@stop