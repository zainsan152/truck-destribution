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
            ['firstname' => 'Chauffeur', 'lastname' => '1'],
            ['firstname' => 'Chauffeur', 'lastname' => '2'],
            ['firstname' => 'Chauffeur', 'lastname' => '3'],
        ];

        DB::table('drivers')->insert($drivers);
    }
}
