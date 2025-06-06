<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Employee Demo',
            'email' => 'pegawai@akhdani.com',
            'password' => Hash::make('password'),
            'role' => 'PEGAWAI',
            'employee_id' => 'EMP001',
            'department' => 'IT',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'HR Manager',
            'email' => 'sdm@akhdani.com',
            'password' => Hash::make('password'),
            'role' => 'SDM',
            'employee_id' => 'HR001',
            'department' => 'HR',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'John Doe',
            'email' => 'john@akhdani.com',
            'password' => Hash::make('password'),
            'role' => 'PEGAWAI',
            'employee_id' => 'EMP002',
            'department' => 'Marketing',
            'is_active' => true,
        ]);
        User::create(
            ['email' => 'admin@akhdani.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'ADMIN',
                'employee_id' => 'ADM001',
                'department' => 'Administration',
                'is_active' => true,
            ]
        );
    }
}
