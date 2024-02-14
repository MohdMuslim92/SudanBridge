<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ['Pending', 'In Transit', 'Out for Delivery', 'Delivered', 'Failed'];

        foreach ($statuses as $statusName) {
            Status::create(['name' => $statusName]);
        }
    }
}
