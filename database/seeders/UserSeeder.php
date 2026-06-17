<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Tài khoản Admin
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'full_name' => 'System Administrator',
                'user_name' => 'admin',
                'phone' => '0987654321',
                'password' => Hash::make('password'),
                'dob' => '1990-01-01',
                'address' => 'Hanoi, Vietnam',
                'role' => 'admin',
            ]
        );

        // 2. Tài khoản Staff (Tương ứng với vai trò Nhân viên/Author hỗ trợ quản lý)
        // Hệ thống sử dụng giá trị enum 'staff' trong database schema
        User::updateOrCreate(
            ['email' => 'staff@gmail.com'],
            [
                'full_name' => 'Ticket Staff',
                'user_name' => 'staff',
                'phone' => '0987654322',
                'password' => Hash::make('password'),
                'dob' => '1995-05-05',
                'address' => 'Hai Phong, Vietnam',
                'role' => 'staff',
            ]
        );

        // 3. Tài khoản Customer
        User::updateOrCreate(
            ['email' => 'customer@gmail.com'],
            [
                'full_name' => 'Regular Customer',
                'user_name' => 'customer',
                'phone' => '0987654323',
                'password' => Hash::make('password'),
                'dob' => '2000-09-09',
                'address' => 'Hanoi, Vietnam',
                'role' => 'customer',
            ]
        );
    }
}
