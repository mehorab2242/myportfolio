<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Mehorab',
                'username' => 'admin',
                'password' => Hash::make('12345678'),
                'role' => 'super_admin',
                'status' => true,
            ]
        );
    }
}
