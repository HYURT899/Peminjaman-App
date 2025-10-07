@extends('adminlte::page')

@section('title', 'Daftar Peminjaman Asset')

@section('content_header')
    <h1 class="text-xl text-bold">Daftar Peminjaman Asset</h1>
@stop

@section('content')
    {{-- Filter & Tombol --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center">
            <label class="mr-2">Filter Status:</label>
            <select id="statusFilter" class="form-control" style="width: 200px;">
                <option value="">Semua Status</option>
                <option value="menunggu">Menunggu</option>
                <option value="disetujui">Disetujui</option>
                <option value="ditolak">Ditolak</option>
                <option value="dikembalikan">Dikembalikan</option>
            </select>
        </div>
        <div>
            <a href="{{ route('admin.peminjam.create') }}" class="btn btn-primary btn-around">
                <i class="fa fa-plus pr-2"></i>
                Tambah Data
            </a>
        </div>
    </div>

    <div class="container-fluid my-4">
        <div class="table-responsive">
            <table id="peminjamanTable" class="table table-hover table-bordered w-100" width="100%">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Peminjam</th>
                        <th>Asset</th>
                        <th>Jumlah total</th>
                        <th>Tgl Pinjam</th>
                        <th>Keperluan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($peminjam as $pinjam)
                        <tr class="table-light">
                            <td>{{ $loop->iteration }}</td>

                            {{-- Nama peminjam --}}
                            <td>{{ $pinjam->nama_peminjam }}</td>

                            {{-- Asset: tampilkan per baris --}}
                            <td>{!! $pinjam->assets !!}</td>

                            {{-- Jumlah total (jika masih mau menunjukkan total) --}}
                            <td>{{ $pinjam->total_jumlah }}</td>

                            {{-- Tanggal pinjam --}}
                            <td>{{ $pinjam->tanggal_pinjam }}</td>

                            {{-- Keperluan (gabungan unik) --}}
                            <td>{{ Str::limit($pinjam->keperluan, 50) }}</td>

                            {{-- Status --}}
                            <td>
                                <span
                                    class="badge 
                                        @if ($pinjam->status == 'menunggu') badge-warning
                                        @elseif($pinjam->status == 'disetujui') badge-success
                                        @elseif($pinjam->status == 'ditolak') badge-danger
                                        @elseif($pinjam->status == 'dikembalikan') badge-info @endif">
                                    {{ ucfirst($pinjam->status) }}
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td>
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    {{-- Aksi utama mengarah ke salah satu record (first) --}}
                                    <a href="{{ route('admin.peminjam.show', $pinjam->id) }}" class="btn btn-info btn-sm"
                                        title="Detail">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.peminjam.edit', $pinjam->id) }}"
                                        class="btn btn-warning btn-sm ml-1" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>

                                    {{-- APPROVE ALL --}}
                                    @if ($pinjam->status == 'menunggu')
                                        <form action="{{ route('admin.peminjam.approve', $pinjam->nama_peminjam) }}"
                                            method="POST" style="display:inline-block;"
                                            onsubmit="return confirm('Yakin ingin menyetujui semua peminjaman dari {{ $pinjam->nama_peminjam }}?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm ml-1">
                                                <i class="fa fa-check"></i>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- REJECT ALL --}}
                                    @if ($pinjam->status == 'menunggu')
                                        <form action="{{ route('admin.peminjam.reject', $pinjam->nama_peminjam) }}"
                                            method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- RETURN ALL --}}
                                    @if ($pinjam->status == 'disetujui')
                                        <form action="{{ route('admin.peminjam.return', $pinjam->nama_peminjam) }}"
                                            method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="fa fa-undo" title="Return"></i> 
                                            </button>
                                        </form>
                                    @endif

                                    {{-- PRINT ALL --}}
                                    @if ($pinjam->status == 'disetujui')
                                        <a href="{{ route('admin.peminjam.cetak', $pinjam->id) }}" target="_blank" class="btn btn-sm btn-primary ml-1 rounded" style="display:inline-block;">
                                            <i class="fa fa-print"title="Print"></i>
                                        </a>
                                    @endif

                                    <!-- TOMBOL DELETE -->
                                    <form action="{{ route('admin.peminjam.destroy', $pinjam->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin hapus asset ini?')" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm ml-1" title="Hapus">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        .btn-group .btn {
            margin-right: 3px;
        }

        .btn-group .btn:last-child {
            margin-right: 0;
        }

        .badge {
            padding: 0.35em 0.65em;
            font-size: 0.75em;
        }
    </style>
@stop

@section('js')
    {{-- Script buat table --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#peminjamanTable').DataTable({
                "pageLength": 10,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Data tidak ditemukan",
                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                    "infoEmpty": "Tidak ada data tersedia",
                    "infoFiltered": "(disaring dari _MAX_ total data)",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                },
                "columnDefs": [{
                    "orderable": false,
                    "targets": [0, 7] // kolom checkbox + aksi
                }]
            });

            // Filter Status
            $('#statusFilter').on('change', function() {
                var status = $(this).val();
                table.column(7).search(status ? '^' + status + '$' : '', true, false).draw();
            });
        });
    </script>

    {{-- Script buat flash message --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        @foreach (['success', 'error', 'warning', 'info'] as $type)
            @if (Session::has($type))
                toastr.{{ $type }}("{{ Session::get($type) }}");
            @endif
        @endforeach
    </script>
@stop
