@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container py-4">
        <!-- Header Dashboard -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Dashboard</h1>
            <div class="text-muted">
                <small>Selamat datang, {{ auth()->user()->name }}</small>
            </div>
        </div>

        @php $user = $user ?? auth()->user(); @endphp

        {{-- Cek Role Admin --}}
        @php
            $isAdmin = false;
            if (method_exists($user, 'hasRole')) {
                $isAdmin = $user->hasRole('admin');
            } else {
                $isAdmin = $user->id === 1;
            }
        @endphp

        {{-- ADMIN VIEW --}}
        @if ($isAdmin)
            <!-- Statistik Cards -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <small class="text-muted fw-semibold">Total Pengguna</small>
                                    <h3 class="mb-0 mt-1">{{ $totalUsers ?? 0 }}</h3>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="bg-primary bg-opacity-10 p-3 rounded">
                                        <i class="fas fa-users text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <small class="text-muted fw-semibold">Total Aset</small>
                                    <h3 class="mb-0 mt-1">{{ $totalAssets ?? 0 }}</h3>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="bg-success bg-opacity-10 p-3 rounded">
                                        <i class="fas fa-boxes text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <small class="text-muted fw-semibold">Total Asset Yang Dipinjam</small>
                                    <h3 class="mb-0 mt-1">{{ $totalPeminjaman ?? 0 }}</h3>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="bg-info bg-opacity-10 p-3 rounded">
                                        <i class="fas fa-hand-holding text-info"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <small class="text-muted fw-semibold">Pending</small>
                                    <h3 class="mb-0 mt-1">{{ $countPending ?? 0 }}</h3>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="bg-warning bg-opacity-10 p-3 rounded">
                                        <i class="fas fa-clock text-warning"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Peminjaman -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0">Peminjaman (Dikelompokkan per Peminjam)</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">No</th>
                                    <th class="border-0">Peminjam</th>
                                    <th class="border-0">Aset</th>
                                    <th class="border-0">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($peminjam as $pinjam)
                                    <tr>
                                        <td class="align-middle">{{ $loop->iteration }}</td>
                                        <td class="align-middle fw-medium">{{ $pinjam->nama_peminjam }}</td>
                                        <td class="align-middle">{!! $pinjam->assets !!}</td>
                                        <td class="align-middle">
                                            <span
                                                class="badge rounded-pill
                                            @if ($pinjam->status == 'menunggu') bg-warning text-dark
                                            @elseif($pinjam->status == 'disetujui') bg-success
                                            @elseif($pinjam->status == 'ditolak') bg-danger
                                            @elseif($pinjam->status == 'dikembalikan') bg-info
                                            @else bg-secondary @endif">
                                                {{ ucfirst($pinjam->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                                <p class="mb-0">Tidak ada data peminjaman.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- USER VIEW --}}
        @else
            <!-- Ringkasan Peminjaman User -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="card-title mb-1">Selamat datang, {{ $user->name }}</h5>
                            <p class="text-muted mb-0">Ringkasan peminjaman Anda</p>
                        </div>
                        <div class="col-md-6">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="card bg-warning bg-opacity-10 border-0 p-3">
                                        <small class="text-muted fw-semibold">Menunggu</small>
                                        <div class="h3 mb-0 text-warning">{{ $countPending ?? 0 }}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card bg-success bg-opacity-10 border-0 p-3">
                                        <small class="text-muted fw-semibold">Disetujui</small>
                                        <div class="h3 mb-0 text-success">{{ $countApproved ?? 0 }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Peminjaman User -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0">Daftar Peminjaman Saya</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">No</th>
                                    <th class="border-0">Peminjam</th>
                                    <th class="border-0">Aset</th>
                                    <th class="border-0">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($peminjam as $pinjam)
                                    <tr>
                                        <td class="align-middle">{{ $loop->iteration }}</td>
                                        <td class="align-middle fw-medium">{{ $pinjam->nama_peminjam }}</td>
                                        <td class="align-middle">{!! $pinjam->assets !!}</td>
                                        <td class="align-middle">
                                            <span
                                                class="badge rounded-pill
                                            @if ($pinjam->status == 'menunggu') bg-warning text-dark
                                            @elseif($pinjam->status == 'disetujui') bg-success
                                            @elseif($pinjam->status == 'ditolak') bg-danger
                                            @elseif($pinjam->status == 'dikembalikan') bg-info
                                            @else bg-secondary @endif">
                                                {{ ucfirst($pinjam->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                                <p class="mb-0">Tidak ada data peminjaman.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

    </div>

    <!-- Tambahkan Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection
