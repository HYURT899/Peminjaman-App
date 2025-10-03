@extends('adminlte::page')

@section('title', 'Daftar Asset')

@section('content_header')
    <h1 class="text-xl text-bold">Daftar Assets</h1>
@stop

@section('content')
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <label class="mr-2 mb-0">Filter Kategori:</label>
            <select id="categoryFilter" class="form-control ml-2" style="width: 220px;">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->name }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="d-flex align-items-center">
            <a href="{{ route('admin.assets.create') }}" class="btn btn-primary btn-around">
                <i class="fa fa-plus pr-2"></i> Tambah data
            </a>
        </div>
    </div>

    <div class="container-fluid my-4">
        <div class="table-responsive">
            <table id="assetTable" class="table table-hover table-bordered w-100" width="100%">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Gambar Asset</th>
                        <th>QR Code</th>
                        <th>Code Asset</th>
                        <th>Nama Asset</th>
                        <th>Deskripsi</th>
                        <th>Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach ($assets as $item)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>
                                @if ($item->gambar)
                                    <img src="{{ asset('storage/' . $item->gambar) }}" alt="Gambar Asset" width="90"
                                        class="img-thumbnail img-clickable"
                                        data-full="{{ asset('storage/' . $item->gambar) }}">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td>
                                @if ($item->qr_code)
                                    <img src="{{ asset('storage/' . $item->qr_code) }}" alt="QR Code" width="90"
                                        class="img-thumbnail img-clickable"
                                        data-full="{{ asset('storage/' . $item->qr_code) }}">
                                @else
                                    <span class="text-muted">No QR</span>
                                @endif
                            </td>
                            <td>{{ $item->kode_asset }}</td>
                            <td>{{ $item->nama_asset }}</td>
                            <td>{{ Str::limit($item->deskripsi, 60) }}</td>
                            <td>{{ $item->kategori->name ?? ($item->category->name ?? 'Tidak ada kategori') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.assets.show', $item->id) }}" class="btn btn-info btn-sm mr-2"
                                        title="Detail">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.assets.edit', $item->id) }}"
                                        class="btn btn-warning btn-sm mr-2" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @if ($item->qr_code)
                                        <a href="{{ asset('storage/' . $item->qr_code) }}"
                                            class="btn btn-secondary btn-sm mr-2"
                                            download="qrcode-{{ $item->kode_asset }}.png" title="Download QR Code">
                                            <i class="fa fa-download"></i>
                                        </a>
                                    @endif
                                    <form action="{{ route('admin.assets.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin hapus asset ini?')" style="display:inline;">
                                        @csrf @method('DELETE')
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

    <!-- Modal untuk tampilan besar foto, foto asset sama qr code -->
    <div class="modal fade" id="ImgModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-transparent border-0 shadow-none">
                <div class="modal-body text-center p-0">
                    <img id="ModalImg" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        .img-thumbnail {
            padding: 2px;
            border-radius: 4px;
            max-height: 90px;
            object-fit: cover;
            cursor: pointer;
        }
    </style>
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(function() {
            // Initialize DataTable
            var table = $('#assetTable').DataTable({
                pageLength: 10,
                lengthChange: true,
                ordering: true,
                searching: true,
                columnDefs: [{
                    orderable: false,
                    targets: [1, 2, 7]
                }],
                language: {
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
                }
            });

            // Klik QR untuk buka modal
            $('.img-clickable').on('click', function() {
                const src = $(this).data('full');
                $('#ModalImg').attr('src', src);
                const modal = new bootstrap.Modal(document.getElementById('ImgModal'));
                modal.show();
            });

            // Category filter
            $('#categoryFilter').on('change', function() {
                var category = $(this).val();
                table.column(6).search(category ? '^' + category + '$' : '', true, false).draw();
            });
        });
    </script>

    {{-- Toastr --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        @foreach (['success', 'error', 'warning', 'info'] as $type)
            @if (Session::has($type))
                toastr.{{ $type }}("{{ Session::get($type) }}");
            @endif
        @endforeach
    </script>
@stop
