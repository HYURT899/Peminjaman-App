@extends('layouts.app')

@section('title', 'Keranjang Peminjaman')

@section('content')
    <div class="container py-4">
        <h1 class="mb-4">Keranjang Peminjaman</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($items->isEmpty())
            <p class="text-muted">Keranjang masih kosong.</p>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Aset</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr data-id="{{ $item->id }}">
                            <td>{{ $item->asset->nama_asset }}</td>
                            <td class="d-flex align-items-center">
                                <!-- Tombol minus -->
                                <button type="button" class="btn btn-sm btn-danger update-qty" data-id="{{ $item->id }}"
                                    data-action="decrease">-</button>

                                <!-- Jumlah -->
                                <span class="mx-2 jumlah">{{ $item->jumlah }}</span>

                                <!-- Tombol plus -->
                                <button type="button" class="btn btn-sm btn-success update-qty"
                                    data-id="{{ $item->id }}" data-action="increase">+</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>


            <form action="{{ route('keranjang.submit') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="nama_peminjam" class="form-label">Nama Peminjam</label>
                    <input type="text" class="form-control" id="nama_peminjam" name="nama_peminjam" required>
                </div>

                <div class="mb-3">
                    <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam</label>
                    <input type="date" class="form-control" id="tanggal_pinjam" name="tanggal_pinjam" required>
                </div>

                <div class="mb-3">
                    <label for="keperluan" class="form-label">Keperluan</label>
                    <textarea class="form-control" id="keperluan" name="keperluan" rows="3" required></textarea>
                </div>

                <input type="hidden" id="status" name="status" value="menunggu">

                <div class="mb-3">
                    <label for="catatan" class="form-label">Catatan (opsional)</label>
                    <input type="text" class="form-control" id="catatan" name="catatan" value="-">
                </div>

                <button type="submit" class="btn btn-success">Ajukan Peminjaman</button>
            </form>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $(".update-qty").click(function() {
                let button = $(this);
                let id = button.data("id");
                let action = button.data("action");
                let row = button.closest("tr");
                let jumlahElem = row.find(".jumlah");

                $.ajax({
                    url: `/keranjang/${id}/update`,
                    method: "PATCH",
                    data: {
                        _token: $("meta[name='csrf-token']").attr("content"),
                        action: action
                    },
                    success: function(res) {
                        console.log("Response:", res);

                        if (res.deleted) {
                            row.remove(); // kalau jumlah = 0, hapus row
                        } else {
                            jumlahElem.text(res.jumlah); // update HTML langsung
                        }
                    },
                    error: function(err) {
                        console.error(err);
                        alert("Gagal update jumlah");
                    }
                });
            });
        });
    </script>
@endpush
