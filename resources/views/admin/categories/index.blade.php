@extends('adminlte::page')

@section('title', 'Daftar Kategori')

@section('content_header')
    <h1 class="text-xl text-bold">Daftar Kategori</h1>
@stop

@section('content')
<div class="container">
    <a href="{{ route('categories.create') }}" class="btn btn-primary mb-3">Tambah Kategori</a>
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $index => $category)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $category->name }}</td>
                <td>
                    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus kategori ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Tidak ada kategori.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@section('js')
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