@extends('adminlte::page')

@section('title', 'Daftar Asset')

@section('content_header')
    <h1 class="text-xl text-bold">Daftar Assets</h1>
@stop

@section('content')
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
                        <th>Gambar</th>
                        <th>Code Asset</th>
                        <th>Nama Asset</th>
                        <th>Deskripsi</th>
                        <th>Stok</th>
                        <th>Kategori</th>
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
                                    <img src="{{ asset('storage/' . $item->gambar) }}" alt="Asset Image" width="100">
                                @else
                                    <span>No Image</span>
                                @endif
                            </td>
                            <td>{{ $item->kode_asset }}</td>
                            <td>{{ $item->nama_asset }}</td>
                            <td>{{ $item->deskripsi }}</td>
                            <td>{{ $item->stok }}</td>
                            <td>{{ $item->kategori->name ?? 'Tidak ada kategori' }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.assets.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>

                                    <form action="{{ route('admin.assets.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin hapus asset ini?')" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" data-toggle="tooltip"
                                            title="Hapus">
                                            <i class="fa fa-times"></i> Hapus
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
@stop

@section('js')
    {{-- Scirpt buat data table --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#assetTable').DataTable({
                "pageLength": 5,
                "lengthChange": true,
                "searching": true,
                "ordering": true
            });

            // Category filter functionality
            $('#categoryFilter').on('change', function() {
                var category = $(this).val();
                table.column(4) // index of category column (0-based)
                    .search(category)
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
