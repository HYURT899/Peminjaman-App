@extends('adminlte::page')

@section('title', 'Manajemen Assets')

@section('content_header')
    <h1 class="text-xl text-bold">Kelola User</h1>
@stop

@section('content')
    <div class="d-flex">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-round ml-auto mb-3">
            <i class="fa fa-plus"></i>
            Tambah User
        </a>
    </div>

    <div class="container-fluid my-4">
        <div class="table-responsive">
            <table id="userTable" class="table table-hover w-100" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Jabatan</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach ($users as $user)
                        <tr class="table-light">
                            <td>{{ $no++ }}</td>
                            <td>
                                @if ($user->gambar)
                                    <img src="{{ asset('storage/' . $user->gambar) }}" alt="User Image" width="80">
                                @else
                                    <span>No Image</span>
                                @endif
                            </td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->jabatan }}</td>
                            <td>@if ($user->role == 1)
                                    <p>Admin</p>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus user?')">Delete</button>
                                </form>
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
            $('#userTable').DataTable({
                "pageLength": 5,
                "lengthChange": true,
                "searching": true,
                "ordering": true
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
