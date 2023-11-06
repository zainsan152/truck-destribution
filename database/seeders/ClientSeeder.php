<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            ['name_client' => 'AXE', 'id_city' => 1],
            ['name_client' => 'Client1', 'id_city' => 1],
            ['name_client' => 'Client2', 'id_city' => 1],
        ];

        DB::table('clients')->insert($clients);
    }
}
