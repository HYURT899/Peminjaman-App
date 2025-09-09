@extends('adminlte::page')

@section('title', 'Daftar Asset')

@section('content_header')
    <h1 class="text-xl text-bold">Daftar Assets</h1>
@stop

@section('content')
    {{-- Filter Kategori --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center">
            <label class="mr-2">Filter Kategori:</label>
            <select id="categoryFilter" class="form-control" style="width: 200px;">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->name }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <a href="{{ route('admin.assets.create') }}" class="btn btn-primary btn-around">
            <i class="fa fa-plus pr-2"></i>
            Tambah data
        </a>
    </div>

    <div class="container-fluid my-4">
        <div class="table-responsive">
            <table id="assetTable" class="table table-hover w-100" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Gambar Asset</th>
                        <th>QR Code</th>
                        <th>Code Asset</th>
                        <th>Nama Asset</th>
                        <th>Deskripsi</th>
                        <th>Kategori</th> <!-- INI KOLOM KE-7 (index 6) -->
                        <th>Aksi</th> 
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach ($assets as $item)
                        <tr class="table-light">
                            <td>{{ $no++ }}</td>
                            <td>
                                @if ($item->gambar)
                                    <img src="{{ asset('storage/' . $item->gambar) }}" alt="Gambar Asset" width="100">
                                @endif
                            </td>
                            <td>
                                @if ($item->qr_code)
                                    <img src="{{ asset('storage/' . $item->qr_code) }}" alt="QR Code" width="100">
                                @endif
                            </td>
                            <td>{{ $item->kode_asset }}</td>
                            <td>{{ $item->nama_asset }}</td>
                            <td>{{ Str::limit($item->deskripsi, 50) }}</td>
                            <td>{{ $item->kategori->name ?? 'Tidak ada kategori' }}</td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <!-- TOMBOL DETAIL -->
                                    <a href="{{ route('admin.assets.show', $item->id) }}" class="btn btn-info btn-sm"
                                        title="Detail">
                                        <i class="fa fa-eye"></i>
                                    </a>

                                    <!-- TOMBOL EDIT -->
                                    <a href="{{ route('admin.assets.edit', $item->id) }}" class="btn btn-warning btn-sm"
                                        title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>

                                    <!-- TOMBOL DOWNLOAD QR CODE -->
                                    @if ($item->qr_code)
                                        <a href="{{ asset('storage/' . $item->qr_code) }}"
                                            download="qrcode-{{ $item->kode_asset }}.png" class="btn btn-secondary btn-sm"
                                            title="Download QR Code">
                                            <i class="fa fa-download"></i>
                                        </a>
                                    @endif

                                    <!-- TOMBOL DELETE -->
                                    <form action="{{ route('admin.assets.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin hapus asset ini?')" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
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
            margin-right: 5px;
        }

        .btn-group .btn:last-child {
            margin-right: 0;
        }

        .img-thumbnail {
            padding: 2px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }
    </style>
@stop

@section('js')
    {{-- Script buat data table --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#assetTable').DataTable({
                "pageLength": 5,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "language": { // Hapus kolom language ini jika ingin jadi bahasa inggris (default)
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
                    "targets": [0, 1, 2, 7] // Kolom No, Gambar, QR Code, dan Aksi tidak bisa di-sort
                }]
            });

            $('#categoryFilter').on('change', function() {
                var category = $(this).val();
                table.column(6) // Index 6 untuk kolom Kategori (0-based, di mulai dari 0)
                    .search(category ? '^' + category + '$' : '', true, false)
                    .draw();
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
