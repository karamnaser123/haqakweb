<?php

namespace Database\Seeders;

use App\Models\Governorate;
use Illuminate\Database\Seeder;

class GovernorateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $governorates = [
            ['name_en' => 'Amman', 'name_ar' => 'عمان'],
            ['name_en' => 'Irbid', 'name_ar' => 'إربد'],
            ['name_en' => 'Zarqa', 'name_ar' => 'الزرقاء'],
            ['name_en' => 'Balqa', 'name_ar' => 'البلقاء'],
            ['name_en' => 'Madaba', 'name_ar' => 'مادبا'],
            ['name_en' => 'Jerash', 'name_ar' => 'جرش'],
            ['name_en' => 'Ajloun', 'name_ar' => 'عجلون'],
            ['name_en' => 'Karak', 'name_ar' => 'الكرك'],
            ['name_en' => 'Tafilah', 'name_ar' => 'الطفيلة'],
            ['name_en' => 'Maan', 'name_ar' => 'معان'],
            ['name_en' => 'Aqaba', 'name_ar' => 'العقبة'],
            ['name_en' => 'Mafraq', 'name_ar' => 'المفرق'],
        ];

        foreach ($governorates as $governorate) {
            Governorate::create($governorate);
        }
    }
}
