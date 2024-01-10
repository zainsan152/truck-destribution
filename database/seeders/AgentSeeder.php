<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agents = [
            ['name' => 'agent1'],
            ['name' => 'agent2'],
            ['name' => 'agent3'],
            ['name' => 'agent4'],
        ];

        DB::table('agents')->insert($agents);
    }
}
