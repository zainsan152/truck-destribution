<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([UsersTableSeeder::class, CitySeeder::class, TruckCategorySeeder::class, ClientSeeder::class, DistributionTypeSeeder::class, VehicleSeeder::class, DriverSeeder::class, ArrivalType::class, ArrivalLineType::class, DeliveryPointSeeder::class]);
//         \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
