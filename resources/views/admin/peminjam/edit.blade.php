@extends('adminlte::page')

@section('title', 'Edit Peminjaman')

@section('content_header')
    <h1 class="text-xl text-bold">Edit Peminjaman</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.peminjam.update', $peminjaman->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user_id">Peminjam</label>
                                    <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                                        <option value="">Pilih Peminjam</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id', $peminjaman->user_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="asset_id">Asset yang Dipinjam</label>
                                    <select name="asset_id" id="asset_id" class="form-control @error('asset_id') is-invalid @enderror" required>
                                        <option value="">Pilih Asset</option>
                                        @foreach ($assets as $asset)
                                            <option value="{{ $asset->id }}" {{ old('asset_id', $peminjaman->asset_id) == $asset->id ? 'selected' : '' }}>
                                                {{ $asset->kode_asset }} - {{ $asset->nama_asset }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('asset_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="jumlah">Jumlah</label>
                                    <input type="number" name="jumlah" id="jumlah" min="1" 
                                        value="{{ old('jumlah', $peminjaman->jumlah) }}"
                                        class="form-control @error('jumlah') is-invalid @enderror" required>
                                    @error('jumlah')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tanggal_pinjam">Tanggal Pinjam</label>
                                    <input type="date" name="tanggal_pinjam" id="tanggal_pinjam"
                                        class="form-control @error('tanggal_pinjam') is-invalid @enderror"
                                        value="{{ old('tanggal_pinjam', $peminjaman->tanggal_pinjam) }}" required>
                                    @error('tanggal_pinjam')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                        <option value="menunggu" {{ old('status', $peminjaman->status) == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                        <option value="disetujui" {{ old('status', $peminjaman->status) == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                        <option value="ditolak" {{ old('status', $peminjaman->status) == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                        <option value="dikembalikan" {{ old('status', $peminjaman->status) == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="keperluan">Keperluan Peminjaman</label>
                            <textarea name="keperluan" id="keperluan" class="form-control @error('keperluan') is-invalid @enderror" 
                                rows="3" placeholder="Jelaskan keperluan peminjaman..." required>{{ old('keperluan', $peminjaman->keperluan) }}</textarea>
                            @error('keperluan')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="catatan">Catatan</label>
                            <textarea name="catatan" id="catatan" class="form-control @error('catatan') is-invalid @enderror" 
                                rows="2" placeholder="Catatan tambahan...">{{ old('catatan', $peminjaman->catatan) }}</textarea>
                            @error('catatan')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Tampilkan info approval jika sudah disetujui -->
                        @if($peminjaman->disetujui_oleh && $peminjaman->disetujui_pada)
                        <div class="alert alert-info mt-3">
                            <strong>Info Approval:</strong><br>
                            Disetujui oleh: {{ $peminjaman->disetujuiOleh->name ?? 'Admin' }}<br>
                            Pada: {{ $peminjaman->disetujui_pada->format('d/m/Y H:i') }}
                        </div>
                        @endif

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Perbarui Peminjaman
                            </button>
                            <a href="{{ route('admin.peminjam.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
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
    </style>
@stop

@section('js')
    <script>
        // Set min date untuk tanggal pinjam
        document.addEventListener('DOMContentLoaded', function() {
            const tanggalPinjam = document.getElementById('tanggal_pinjam');
            const today = new Date().toISOString().split('T')[0];
            tanggalPinjam.min = today;
        });
    </script>
@stop