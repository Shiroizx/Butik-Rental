<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user
        Employee::create([
            'nama' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'no_telepon' => '081234567890',
            'alamat' => 'Butik Pusat',
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Staff user
        Employee::create([
            'nama' => 'Staff Biasa',
            'email' => 'staff@staff.com',
            'password' => Hash::make('password'),
            'no_telepon' => '08987654321',
            'alamat' => 'Butik Cabang',
            'role' => 'staff',
            'is_active' => true,
        ]);
    }
}
