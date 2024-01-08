<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArrivalType extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['type' => 'Maritime'],
            ['type' => 'Aérien'],
            ['type' => 'Routier'],
        ];

        DB::table('arrival_types')->insert($types);
    }
}
