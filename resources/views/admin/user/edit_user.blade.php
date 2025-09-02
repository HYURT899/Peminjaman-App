@extends('adminlte::page')

@section('title', 'Tambah User')

@section('content')
<div class="container">
    <h2>Tambah User</h2>
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" value="{{ old('name',$user->name) }}" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>
        <div class="mb-3">
    <label>Password</label>
    <input type="password" name="password" class="form-control" value="{{ old('password',$user->password) }}"  required>
</div>
        <div class="mb-3">
            <label>Jabatan</label>
            <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan',$user->jabatan) }}">
        </div>
        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-control" required>
                @foreach($roleNames as $id => $name)
                    <option value="{{ $id }}" 
                        {{ old('role_id', $user->role_id) == $id ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Gambar</label>
            <input type="file" name="gambar" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@stop