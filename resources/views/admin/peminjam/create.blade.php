@extends('adminlte::page')

@section('title', 'Tambah Peminjaman Baru')

@section('content_header')
    <h1 class="text-xl text-bold">Tambah Peminjaman Baru</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.peminjam.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_peminjam" class="required-label">Nama Peminjam</label>
                                    <input type="text" name="nama_peminjam" id="nama_peminjam"
                                        class="form-control @error('nama_peminjam') is-invalid @enderror"
                                        value="{{ old('nama_peminjam') }}" required placeholder="Masukkan nama peminjam">
                                    @error('nama_peminjam')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="asset_id" class="required-label">Asset yang Dipinjam</label>
                                    <select name="asset_id" id="asset_id"
                                        class="form-control @error('asset_id') is-invalid @enderror" required>
                                        <option value="">Pilih Asset</option>
                                        @foreach ($assets as $asset)
                                            <option value="{{ $asset->id }}"
                                                {{ old('asset_id') == $asset->id ? 'selected' : '' }}
                                                data-stok="{{ $asset->stok }}">
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
                                    <label for="jumlah" class="required-label">Jumlah</label>
                                    <input type="number" name="jumlah" id="jumlah" min="1"
                                        value="{{ old('jumlah', 1) }}"
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
                                    <label for="tanggal_pinjam" class="required-label">Tanggal Pinjam</label>
                                    <input type="date" name="tanggal_pinjam" id="tanggal_pinjam"
                                        class="form-control @error('tanggal_pinjam') is-invalid @enderror"
                                        value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required>
                                    @error('tanggal_pinjam')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                        </div>

                        <div class="form-group">
                            <label for="keperluan" class="required-label">Keperluan Peminjaman</label>
                            <textarea name="keperluan" id="keperluan" class="form-control @error('keperluan') is-invalid @enderror" rows="3"
                                placeholder="Jelaskan keperluan peminjaman..." required>{{ old('keperluan') }}</textarea>
                            @error('keperluan')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="status" class="required-label">Status</label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror"
                                required>
                                <option value="menunggu" {{ old('status') == 'menunggu' ? 'selected' : '' }}>Menunggu
                                </option>
                                <option value="disetujui" {{ old('status') == 'disetujui' ? 'selected' : '' }}>Disetujui
                                </option>
                                <option value="ditolak" {{ old('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="catatan">Catatan (Opsional)</label>
                            <textarea name="catatan" id="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="2"
                                placeholder="Catatan tambahan...">{{ old('catatan', '-') }}</textarea>
                            @error('catatan')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Peminjaman
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

        .required-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
        }

        .required-label::after {
            content: " *";
            color: #dc3545;
            /* Merah Bootstrap */
            font-weight: bold;
        }

        /* Optional: Style untuk form groups */
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        /* Optional: Tooltip info */
        .label-tooltip {
            cursor: help;
            border-bottom: 1px dotted #999;
        }
    </style>
@stop

@section('js')
    <script>
        // Event listeners
        document.getElementById('asset_id').addEventListener('change', updateStokInfo);
        document.getElementById('jumlah').addEventListener('input', updateStokInfo);


        document.querySelector('form').addEventListener('submit', function(e) {
            const assetSelect = document.getElementById('asset_id');
            const jumlahInput = document.getElementById('jumlah');

            if (assetSelect.selectedIndex > 0) {
                const selectedOption = assetSelect.options[assetSelect.selectedIndex];
                const stokTersedia = parseInt(selectedOption.getAttribute('data-stok'));
                const jumlah = parseInt(jumlahInput.value);

                if (jumlah > stokTersedia) {
                    e.preventDefault();
                    alert('Jumlah peminjaman melebihi stok tersedia! Stok tersedia: ' + stokTersedia);
                    jumlahInput.focus();
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
    const toggleButton = document.getElementById('toggleBulkAction');
    const deleteButton = document.getElementById('deleteSelected');
    const bulkCheckboxes = document.querySelectorAll('.bulk-checkbox');
    const selectAll = document.getElementById('selectAll');
    let bulkMode = false;

    toggleButton.addEventListener('click', function () {
        bulkMode = !bulkMode;

        bulkCheckboxes.forEach(cb => {
            cb.classList.toggle('d-none', !bulkMode);
        });

        deleteButton.classList.toggle('d-none', !bulkMode);

        // Reset semua checkbox saat keluar dari mode bulk
        if (!bulkMode) {
            document.querySelectorAll('input[name="ids[]"]').forEach(chk => chk.checked = false);
            if (selectAll) selectAll.checked = false;
        }
    });

    // Select all
    if (selectAll) {
        selectAll.addEventListener('change', function () {
            const all = document.querySelectorAll('input[name="ids[]"]');
            all.forEach(chk => chk.checked = selectAll.checked);
        });
    }

    // Hapus terpilih
    deleteButton.addEventListener('click', function () {
        const selected = document.querySelectorAll('input[name="ids[]"]:checked');
        if (selected.length === 0) {
            alert('Pilih minimal satu data untuk dihapus.');
            return;
        }

        if (confirm('Yakin mau menghapus data yang dipilih?')) {
            document.getElementById('bulkDeleteForm').submit();
        }
    });
});
    </script>
@stop
