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
        $roles = DB::table('roles')->pluck('role_name', 'id');
        return view('admin.user.users', compact('users', 'roles'));
    }

    public function create()
    {
        $roleNames = DB::table('roles')->pluck('role_name', 'id');
        return view('admin.user.create_user', compact('roleNames'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'jabatan' => 'required',
            'role' => 'required|integer',
            'password' => 'required|min:6',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();
        $data['password'] = bcrypt($request->password);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('users', 'public');
        }

        User::create($data);
        return redirect()->route('admin.user.users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function show(User $user)
    {
        return view('admin.user.show_user', compact('user'));
    }

    public function edit(User $user)
    {
        $roleNames = DB::table('roles')->pluck('role_name', 'id');
        return view('admin.user.edit_user', compact('user', 'roleNames'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'jabatan' => 'required',
            'role' => 'required|integer',
            'password' => 'nullable|min:6',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('users', 'public');
        }

        $user->update($data);
        return redirect()->route('admin.user.users.index')->with('success', 'User berhasil diupdate');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.user.users.index')->with('success', 'User berhasil dihapus');
    }
}
