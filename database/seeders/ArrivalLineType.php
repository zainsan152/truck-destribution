<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArrivalLineType extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['type' => 'Conteneur'],
            ['type' => 'Palette'],
            ['type' => 'Colis'],
            ['type' => 'Autre'],
        ];

        DB::table('arrival_line_types')->insert($types);
    }
}
