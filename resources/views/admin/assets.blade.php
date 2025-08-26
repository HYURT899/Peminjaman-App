@extends('adminlte::page')

@section('title', 'Manajemen Assets')

@section('content_header')
    <h1>Daftar Assets</h1>
@stop

@section('content')
    <div class="container-fluid my-4">
        <div class="table-responsive">
            <table id="assetTable" class="table table-hover w-100">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Code Asset</th>
                        <th>Nama Asset</th>
                        <th>Deskripsi</th>
                        <th>Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($asset as $item)
                        <tr class="table-light">
                            <td>{{ $item->id }}</td>
                            <td>
                                @if ($item->gambar && file_exists(public_path($item->gambar)))
                                    <img src="{{ asset($item->gambar) }}" alt="Asset Image" width="80" height="80">
                                @else
                                    <span>No Image</span>
                                @endif
                            </td>
                            <td>{{ $item->kode_asset }}</td>
                            <td>{{ $item->nama_asset }}</td>
                            <td>{{ $item->deskripsi }}</td>
                            <td>{{ $item->stok }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@stop

{{-- Tambahkan CSS DataTables --}}
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@stop

{{-- Tambahkan JS DataTables --}}
@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#assetTable').DataTable({
                "pageLength": 5,
                "lengthChange": true,
                "searching": true,
                "ordering": true
            });
        });
    </script>
@stop
