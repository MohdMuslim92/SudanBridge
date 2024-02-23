<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\City;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            ['city_name' => 'Portsudan', 'next_city' => 'Atbara', 'distance_to_next_city' => 500],
            ['city_name' => 'Atbara', 'next_city' => 'Khartoum', 'distance_to_next_city' => 330],
            ['city_name' => 'Khartoum', 'next_city' => 'Madani', 'distance_to_next_city' => 180],
            ['city_name' => 'Madani', 'next_city' => 'Algadarif', 'distance_to_next_city' => 235],
            ['city_name' => 'Portsudan', 'next_city' => 'Kasala', 'distance_to_next_city' => 565],
            ['city_name' => 'Kasala', 'next_city' => 'Algadarif', 'distance_to_next_city' => 215],
        ];

        // Insert the data into the cities table
        City::insert($cities);
    }
}
