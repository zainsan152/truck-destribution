<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicles = [
            ['marque_vehicle' => 'Brand A', 'modele_vehicle' => 'Model X', 'immatriculation' => 'ABC123', 'date_acquisition' => '2023-11-01', 'id_truck_category' => 1],
            ['marque_vehicle' => 'Brand B', 'modele_vehicle' => 'Model Y', 'immatriculation' => 'DEF456', 'date_acquisition' => '2023-11-01', 'id_truck_category' => 2],
            ['marque_vehicle' => 'Brand C', 'modele_vehicle' => 'Model Z', 'immatriculation' => 'GHI789', 'date_acquisition' => '2023-11-01', 'id_truck_category' => 3],
        ];

        DB::table('vehicle_fleet')->insert($vehicles);
    }
}
