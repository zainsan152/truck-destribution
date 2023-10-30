<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'user_firstname' => 'John',
            'user_lastname' => 'Doe',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'id_role' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
