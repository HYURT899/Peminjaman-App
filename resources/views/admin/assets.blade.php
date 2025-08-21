@extends('adminlte::page')

@section('title', 'Manajemen Assets')

@section('content_header')
    <h1>Daftar Assets</h1>
@stop

@section('content')
    <div class="container my-4">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Gambar</th>
                    <th scope="col">Code Asset</th>
                    <th scope="col">Nama Asset</th>
                    <th scope="col">Deskripsi</th>
                    <th scope="col">Stok</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($asset as $item)
                    <tr class="table-light">
                        <th>{{ $item->id }}</th>
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

        {{ $asset->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
@stop
