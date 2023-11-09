<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $drivers = [
            ['firstname' => 'driver', 'lastname' => 'one'],
            ['firstname' => 'driver', 'lastname' => 'two'],
            ['firstname' => 'driver', 'lastname' => 'three'],
        ];

        DB::table('drivers')->insert($drivers);
    }
}
