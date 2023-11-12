<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TruckCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('truck_category')->insert(['truck_category' => 'Camion']);
        DB::table('truck_category')->insert(['truck_category' => 'Tracteur']);
        DB::table('truck_category')->insert(['truck_category' => 'Remorque']);
    }
}
