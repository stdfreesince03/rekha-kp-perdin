<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'pegawai@akhdani.com'],
            [
                'name' => 'Employee Demo',
                'password' => Hash::make('password'),
                'role' => 'PEGAWAI',
                'employee_id' => 'EMP001',
                'department' => 'IT',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'sdm@akhdani.com'],
            [
                'name' => 'HR Manager',
                'password' => Hash::make('password'),
                'role' => 'SDM',
                'employee_id' => 'HR001',
                'department' => 'HR',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'john@akhdani.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password'),
                'role' => 'PEGAWAI',
                'employee_id' => 'EMP002',
                'department' => 'Marketing',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
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
