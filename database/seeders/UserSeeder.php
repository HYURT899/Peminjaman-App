<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']); 
        $userRole = Role::firstOrCreate(['name' => 'user']);

        
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'], 
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'), 
                'jabatan' => 'Administrator',
            ]
        );

        $admin->assignRole($adminRole);

        $user = User::firstOrCreate(
            ['email' => 'user@example.com'], 
            [
                'name' => 'User',
                'password' => bcrypt('password'), 
                'jabatan' => 'Pegawai',
            ]
        );

        $user->assignRole($userRole);

        
    }
}