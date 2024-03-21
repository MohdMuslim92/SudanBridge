<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\State;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = [
            ['name' => 'البحر الأحمر'],
            ['name' => 'الجزيرة'],
            ['name' => 'الخرطوم'],
            ['name' => 'الشمالية'],
            ['name' => 'القضارف'],
            ['name' => 'النيل الأبيض'],
            ['name' => 'النيل الأزرق'],
            ['name' => 'جنوب دارفور'],
            ['name' => 'جنوب كردفان'],
            ['name' => 'سنار'],
            ['name' => 'شرق دارفور'],
            ['name' => 'شمال دارفور'],
            ['name' => 'شمال كردفان'],
            ['name' => 'غرب دارفور'],
            ['name' => 'كسلا'],
            ['name' => 'نهر النيل'],
            ['name' => 'وسط دارفور'],
            ['name' => 'غرب كردفان'],
        ];
        foreach ($states as $state) {
            State::create($state);
        }

    }
}
