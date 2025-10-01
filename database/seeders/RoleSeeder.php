<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // buat role
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'user']);

        // contoh assign role ke user tertentu (email harus ada)
        // $u = User::where('email', 'admin@example.com')->first();
        // if ($u) {
        //     $u->assignRole('admin');
        // }
    }
}
