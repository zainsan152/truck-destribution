<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeliveryPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $points = [
            ['name_delivery' => 'Warhouse 1', 'longitude' => 'Model X', 'latitude' => 'ABC123'],
            ['name_delivery' => 'Warhouse 2', 'longitude' => 'Model X', 'latitude' => 'ABC123'],
            ['name_delivery' => 'Warhouse 3', 'longitude' => 'Model X', 'latitude' => 'ABC123'],
            ['name_delivery' => 'Warhouse 4', 'longitude' => 'Model X', 'latitude' => 'ABC123'],
            ['name_delivery' => 'Warhouse 5', 'longitude' => 'Model X', 'latitude' => 'ABC123'],
            ['name_delivery' => 'Warhouse 6', 'longitude' => 'Model X', 'latitude' => 'ABC123'],
            ['name_delivery' => 'Warhouse 7', 'longitude' => 'Model X', 'latitude' => 'ABC123'],
            ['name_delivery' => 'Warhouse 8', 'longitude' => 'Model X', 'latitude' => 'ABC123'],
            ['name_delivery' => 'Warhouse 9', 'longitude' => 'Model X', 'latitude' => 'ABC123'],
            ['name_delivery' => 'Warhouse 1', 'longitude' => 'Model X', 'latitude' => 'ABC123'],
            ['name_delivery' => 'Warhouse 11', 'longitude' => 'Model X', 'latitude' => 'ABC123'],
        ];

        DB::table('delivery_points')->insert($points);
    }
}
