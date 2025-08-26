@extends('adminlte::page')

@section('title', 'Manajemen Assets')

@section('content_header')
    <h1 class="text-xl text-bold">Daftar Assets</h1>
@stop

@section('content')
    <div class="d-flex">
        <button class="btn btn-primary btn-round ml-auto mb-3" data-toggle="modal" data-target="#addRowModal">
            <i class="fa fa-plus"></i>
            Tambah Asset
        </button>
        @include('layouts.modalCreateAssetAdmin')
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
                        <th>Aksi</th> {{-- Tambah kolom aksi --}}
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach ($asset as $item)
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
                            <td>
                                <div class="btn-group" role="group">
                                    {{-- Tombol Edit --}}
                                    <button class="btn btn-sm btn-warning" data-toggle="modal"
                                        data-target="#editRowModal-{{ $item->id }}">
                                        <i class="fa fa-edit"></i> Edit
                                    </button>
                                    @include('layouts.modalEditAssetAdmin')

                                    {{-- Tombol Hapus --}}
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
@stop

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
