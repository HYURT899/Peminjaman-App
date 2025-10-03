@extends('adminlte::page')

@section('title', 'Daftar Kategori')

@section('content_header')
    <h1 class="text-xl text-bold">Daftar Kategori</h1>
@stop

@section('content')
    <div class="d-flex align-items-center">
        <!-- Tombol untuk membuka modal create category -->
        <button type="button" class="btn btn-primary btn-round ml-auto mb-3" data-bs-toggle="modal"
            data-bs-target="#createCategoryModal"><i class="fa fa-plus pr-2"></i>Tambah Kategori</button>
    </div>

    <div class="container-fluid my-4">
        <div class="table-responsive">
            <table id="kategoriTable" class="table table-hover table-bordered w-100" width="100%">
                <thead class="thead-light">
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
                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#editCategoryModal" data-id="{{ $category->id }}"
                                    data-name="{{ $category->name }}">
                                    Edit
                                </button>
                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="btn btn-danger btn-sm"onclick="return confirm('Yakin ingin menghapus kategori ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @include('admin.categories.create')
    @include('admin.categories.edit')
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#kategoriTable').DataTable({
                "pageLength": 10,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "columnDefs": [{
                    "targets": [2],
                    "orderable": false
                }],
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Data tidak ditemukan",
                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                    "infoEmpty": "Tidak ada data tersedia",
                    "infoFiltered": "(disaring dari _MAX_ total data)"
                }
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

    {{-- Script untuk bagian edit kategori --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editModal = document.getElementById('editCategoryModal');

            editModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const categoryId = button.getAttribute('data-id');
                const categoryName = button.getAttribute('data-name');

                const form = document.getElementById('editCategoryForm');
                form.action = '{{ route('categories.update', ['category' => ':id']) }}'.replace(':id',categoryId);

                // Update input value
                document.getElementById('edit_name').value = categoryName;
            });
        });
    </script>
@stop
