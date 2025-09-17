@extends('adminlte::page')

@section('title', 'Tambah User')

@section('content')
@section('content_header')
    <h1 class="text-xl text-bold">Tambah User Baru</h1>
@stop
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card rounded-3">
                <div class="card-body">
                    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" id="name" name="name" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" name="password" class="form-control" required
                                    minlength="6">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="jabatan" class="form-label">Jabatan</label>
                                <input type="text" id="jabatan" name="jabatan" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select id="role" name="role" class="form-control" required>
                                    <option value="" disabled selected>-- Pilih Role --</option>
                                    @foreach ($roleNames as $id => $name)
                                        <option value="{{ $id }}">{{ ucfirst($name) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="gambar" class="form-label">Gambar</label>
                                <input type="file" id="gambar" name="gambar" class="form-control">
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .required-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: block;
    }

    .required-label::after {
        content: " *";
        color: #dc3545;
        /* Merah Bootstrap */
        font-weight: bold;
    }

    /* Optional: Style untuk form groups */
    .form-group {
        margin-bottom: 1.5rem;
        position: relative;
    }

    /* Optional: Tooltip info */
    .label-tooltip {
        cursor: help;
        border-bottom: 1px dotted #999;
    }
</style>
@stop
