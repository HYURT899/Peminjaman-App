<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.user.users', compact('users'));
    }

    public function create()
    {
        return view('admin.user.create_user');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'jabatan' => 'required',
            'password' => 'required|min:6',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();
        $data['password'] = bcrypt($request->password);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('users', 'public');
        }

        $user = User::create($data);

        // Assign role Spatie
        $user->assignRole($request->role);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function show(User $user)
    {
        return view('admin.user.show_user', compact('user'));
    }

    public function edit(User $user)
    {
        $user = User::findOrFail($user->id);
        return view('admin.user.edit_user', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // dd($request->all());

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'jabatan' => 'required',
            'password' => 'nullable|min:6',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except('password');

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        } 

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('users', 'public');
        }

        $user->update($data);
        return redirect()->route('admin.users.index')->with('success', 'User berhasil diupdate');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus');
    }
}
