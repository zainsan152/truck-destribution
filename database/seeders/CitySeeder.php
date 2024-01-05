<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            ['city' => 'Casablanca'],
            ['city' => 'Marrakech'],
            ['city' => 'Rabat'],
			['city' => 'Tanger'],
        ];

        DB::table('city')->insert($cities);
    }
}
