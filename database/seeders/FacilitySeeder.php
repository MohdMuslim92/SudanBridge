<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Facility;
class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = [
            [
                'name' => 'Main store',
                'location' => 'Portsodan',
            ],
            [
                'name' => 'Distribution center',
                'location' => 'Portsodan',
            ],
            [
                'name' => 'Main store',
                'location' => 'Atbra',
            ],
            [
                'name' => 'Distribution center',
                'location' => 'Atbra',
            ],
            [
                'name' => 'Main store',
                'location' => 'Khartoum',
            ],
            [
                'name' => 'Distribution center',
                'location' => 'Khartoum',
            ],
            [
                'name' => 'Distribution center',
                'location' => 'Bahri',
            ],
            [
                'name' => 'Distribution center',
                'location' => 'Omdurman',
            ],
        ];

        Facility::insert($facilities);
    }
}
