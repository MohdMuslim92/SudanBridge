<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserStatus;

class UserStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define an array of status names
        $statusNames = [
            'pending',
            'active',
            'suspended',
            'inactive',
            'deleted'
        ];

        // Loop through the status names and insert them into the statuses table
        foreach ($statusNames as $name) {
            UserStatus::create(['name' => $name]);
        }
    }
}
