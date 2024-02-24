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
                'location' => 'Portsudan',
            ],
            [
                'name' => 'Distribution center',
                'location' => 'Portsudan',
            ],
            [
                'name' => 'Main store',
                'location' => 'Atbara',
            ],
            [
                'name' => 'Distribution center',
                'location' => 'Atbara',
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
