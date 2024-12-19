<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'uuid' => Str::uuid(),
            "name" => "superadmin",
            "email" => "admin@admin.com",
            "password" => Hash::make("password"),
            'role' => '1',
        ]);
    }
}
