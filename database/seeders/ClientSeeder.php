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
            ['code_client' => 'C001', 'name_client' => 'AXE', 'id_city' => 1],
            ['code_client' => 'C002', 'name_client' => 'Client1', 'id_city' => 2],
            ['code_client' => 'C003', 'name_client' => 'Client2', 'id_city' => 3],
            ['code_client' => 'C004', 'name_client' => 'Whp', 'id_city' => 1],
            ['code_client' => 'C005', 'name_client' => 'Cary', 'id_city' => 1],
            ['code_client' => 'C006', 'name_client' => 'Eldx', 'id_city' => 1],
            ['code_client' => 'C007', 'name_client' => 'Savog', 'id_city' => 1],
            ['code_client' => 'C008', 'name_client' => 'Mecifel', 'id_city' => 1],
            ['code_client' => 'C009', 'name_client' => 'Fof', 'id_city' => 1],
            ['code_client' => 'C0010', 'name_client' => 'Socika', 'id_city' => 1],
            ['code_client' => 'C0011', 'name_client' => 'ARKAS CO', 'id_city' => 1],
        ];

        DB::table('clients')->insert($clients);
    }
}
