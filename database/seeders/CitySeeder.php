<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Governorate;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            // Amman Governorate
            'عمان' => [
                ['name_en' => 'Amman', 'name_ar' => 'عمان'],
                ['name_en' => 'Wadi as-Sir', 'name_ar' => 'وادي السير'],
                ['name_en' => 'Naour', 'name_ar' => 'ناعور'],
                ['name_en' => 'Jubeiha', 'name_ar' => 'الجبيهة'],
                ['name_en' => 'Sweileh', 'name_ar' => 'صويلح'],
                ['name_en' => 'Marj al-Hamam', 'name_ar' => 'مرج الحمام'],
                ['name_en' => 'Abu Nsair', 'name_ar' => 'أبو نصير'],
                ['name_en' => 'Khalda', 'name_ar' => 'خلدا'],
                ['name_en' => 'Tla al-Ali', 'name_ar' => 'تلاع العلي'],
                ['name_en' => 'Sahab', 'name_ar' => 'سحاب'],
                ['name_en' => 'Al-Jizah', 'name_ar' => 'الجيزة'],
                ['name_en' => 'Al-Muwaqqar', 'name_ar' => 'الموقر'],
            ],

            // Irbid Governorate
            'إربد' => [
                ['name_en' => 'Irbid', 'name_ar' => 'إربد'],
                ['name_en' => 'Ramtha', 'name_ar' => 'الرمثا'],
                ['name_en' => 'Al-Husn', 'name_ar' => 'الحصن'],
                ['name_en' => 'Bani Kinanah', 'name_ar' => 'بني كنانة'],
                ['name_en' => 'Kourah', 'name_ar' => 'الكورة'],
                ['name_en' => 'Al-Mazar al-Shamali', 'name_ar' => 'المزار الشمالي'],
                ['name_en' => 'Al-Taybeh', 'name_ar' => 'الطيبة'],
                ['name_en' => 'Al-Aghwar al-Shamaliya', 'name_ar' => 'الأغوار الشمالية'],
            ],

            // Zarqa Governorate
            'الزرقاء' => [
                ['name_en' => 'Zarqa', 'name_ar' => 'الزرقاء'],
                ['name_en' => 'Russeifa', 'name_ar' => 'الرصيفة'],
                ['name_en' => 'Hashimiya', 'name_ar' => 'الهاشمية'],
                ['name_en' => 'Al-Azraq', 'name_ar' => 'الأزرق'],
                ['name_en' => 'Al-Sukhna', 'name_ar' => 'السخنة'],
                ['name_en' => 'Bireen', 'name_ar' => 'بيرين'],
                ['name_en' => 'Al-Dhuleil', 'name_ar' => 'الضليل'],
            ],

            // Balqa Governorate
            'البلقاء' => [
                ['name_en' => 'Salt', 'name_ar' => 'السلط'],
                ['name_en' => 'Fuheis', 'name_ar' => 'الفحيص'],
                ['name_en' => 'Mahis', 'name_ar' => 'ماحص'],
                ['name_en' => 'Dair Alla', 'name_ar' => 'دير علا'],
                ['name_en' => 'Ain al-Basha', 'name_ar' => 'عين الباشا'],
                ['name_en' => 'Al-Shouna al-Janubiya', 'name_ar' => 'الشونة الجنوبية'],
            ],

            // Madaba Governorate
            'مادبا' => [
                ['name_en' => 'Madaba', 'name_ar' => 'مادبا'],
                ['name_en' => 'Dhiban', 'name_ar' => 'ذيبان'],
                ['name_en' => 'Mukawir', 'name_ar' => 'مكاور'],
                ['name_en' => 'Lub', 'name_ar' => 'لب'],
                ['name_en' => 'Maliheh', 'name_ar' => 'مليح'],
            ],

            // Jerash Governorate
            'جرش' => [
                ['name_en' => 'Jerash', 'name_ar' => 'جرش'],
                ['name_en' => 'Souf', 'name_ar' => 'سوف'],
                ['name_en' => 'Barma', 'name_ar' => 'برما'],
                ['name_en' => 'Al-Katta', 'name_ar' => 'الكتة'],
                ['name_en' => 'Sakeb', 'name_ar' => 'ساكب'],
            ],

            // Ajloun Governorate
            'عجلون' => [
                ['name_en' => 'Ajloun', 'name_ar' => 'عجلون'],
                ['name_en' => 'Kufranja', 'name_ar' => 'كفرنجة'],
                ['name_en' => 'Anjara', 'name_ar' => 'عنجرة'],
                ['name_en' => 'Sakhra', 'name_ar' => 'صخرة'],
                ['name_en' => 'Abeen', 'name_ar' => 'عبين'],
            ],

            // Karak Governorate
            'الكرك' => [
                ['name_en' => 'Karak', 'name_ar' => 'الكرك'],
                ['name_en' => 'Qasr', 'name_ar' => 'القصر'],
                ['name_en' => 'Al-Mazar al-Janubi', 'name_ar' => 'المزار الجنوبي'],
                ['name_en' => 'Al-Aghwar al-Janubiya', 'name_ar' => 'الأغوار الجنوبية'],
                ['name_en' => 'Mutah', 'name_ar' => 'مؤتة'],
                ['name_en' => 'Al-Qatranah', 'name_ar' => 'القطرانة'],
            ],

            // Tafilah Governorate
            'الطفيلة' => [
                ['name_en' => 'Tafilah', 'name_ar' => 'الطفيلة'],
                ['name_en' => 'Buseira', 'name_ar' => 'البصيرة'],
                ['name_en' => 'Al-Hasa', 'name_ar' => 'الحسا'],
                ['name_en' => 'Al-Qadisiyah', 'name_ar' => 'القادسية'],
            ],

            // Ma'an Governorate
            'معان' => [
                ['name_en' => 'Maan', 'name_ar' => 'معان'],
                ['name_en' => 'Petra', 'name_ar' => 'البتراء'],
                ['name_en' => 'Wadi Musa', 'name_ar' => 'وادي موسى'],
                ['name_en' => 'Al-Shobak', 'name_ar' => 'الشوبك'],
                ['name_en' => 'Al-Husainiya', 'name_ar' => 'الحسينية'],
            ],

            // Aqaba Governorate
            'العقبة' => [
                ['name_en' => 'Aqaba', 'name_ar' => 'العقبة'],
                ['name_en' => 'Wadi Araba', 'name_ar' => 'وادي عربة'],
                ['name_en' => 'Al-Quwaira', 'name_ar' => 'القويرة'],
                ['name_en' => 'Al-Disa', 'name_ar' => 'الديسة'],
                ['name_en' => 'Rahma', 'name_ar' => 'رحمة'],
            ],

            // Mafraq Governorate
            'المفرق' => [
                ['name_en' => 'Mafraq', 'name_ar' => 'المفرق'],
                ['name_en' => 'Al-Ruwaished', 'name_ar' => 'الرويشد'],
                ['name_en' => 'Al-Sabha', 'name_ar' => 'الصبحة'],
                ['name_en' => 'Al-Khalidiya', 'name_ar' => 'الخالدية'],
                ['name_en' => 'Umm al-Jimal', 'name_ar' => 'أم الجمال'],
                ['name_en' => 'Al-Safawi', 'name_ar' => 'الصفاوي'],
                ['name_en' => 'Rahhab', 'name_ar' => 'رحاب'],
                ['name_en' => 'Balama', 'name_ar' => 'بلعما'],
            ],
        ];


        foreach ($cities as $governorateNameAr => $cityList) {
            $governorate = Governorate::where('name_ar', $governorateNameAr)->first();

            if ($governorate) {
                foreach ($cityList as $city) {
                    City::create([
                        'governorate_id' => $governorate->id,
                        'name_en' => $city['name_en'],
                        'name_ar' => $city['name_ar'],
                    ]);
                }
            }
        }
    }
}
