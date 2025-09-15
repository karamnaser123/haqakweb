<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Main category for mobile phones
        $mobileCategory = Category::create([
            'name_en' => 'Mobile Phones',
            'name_ar' => 'هواتف محمولة',
            'parent_id' => null,
            'image' => null,
        ]);

        // Smartphones subcategories
        $smartphonesCategory = Category::create([
            'name_en' => 'Smartphones',
            'name_ar' => 'هواتف ذكية',
            'parent_id' => $mobileCategory->id,
        ]);

        $smartphoneBrands = [
            ['name_en' => 'iPhone', 'name_ar' => 'آيفون'],
            ['name_en' => 'Samsung Galaxy', 'name_ar' => 'سامسونج جالاكسي'],
            ['name_en' => 'Huawei', 'name_ar' => 'هواوي'],
            ['name_en' => 'Xiaomi', 'name_ar' => 'شاومي'],
            ['name_en' => 'OnePlus', 'name_ar' => 'وان بلس'],
            ['name_en' => 'Oppo', 'name_ar' => 'أوبو'],
            ['name_en' => 'Vivo', 'name_ar' => 'فيفو'],
            ['name_en' => 'Realme', 'name_ar' => 'ريلمي'],
            ['name_en' => 'Honor', 'name_ar' => 'هونر'],
            ['name_en' => 'Nokia', 'name_ar' => 'نوكيا'],
            ['name_en' => 'Motorola', 'name_ar' => 'موتورولا'],
            ['name_en' => 'Sony', 'name_ar' => 'سوني'],
        ];

        foreach ($smartphoneBrands as $brand) {
            Category::create([
                'name_en' => $brand['name_en'],
                'name_ar' => $brand['name_ar'],
                'parent_id' => $smartphonesCategory->id,
            ]);
        }

        // Mobile Accessories main category
        $accessoriesCategory = Category::create([
            'name_en' => 'Mobile Accessories',
            'name_ar' => 'إكسسوارات الهواتف',
            'parent_id' => $mobileCategory->id,
        ]);

        // Phone Cases & Covers
        $casesCategory = Category::create([
            'name_en' => 'Phone Cases & Covers',
            'name_ar' => 'أغطية وواقيات الهواتف',
            'parent_id' => $accessoriesCategory->id,
        ]);

        $caseTypes = [
            ['name_en' => 'Silicone Cases', 'name_ar' => 'أغطية السيليكون'],
            ['name_en' => 'Leather Cases', 'name_ar' => 'أغطية الجلد'],
            ['name_en' => 'Clear Cases', 'name_ar' => 'أغطية شفافة'],
            ['name_en' => 'Rugged Cases', 'name_ar' => 'أغطية مقاومة للصدمات'],
            ['name_en' => 'Wallet Cases', 'name_ar' => 'أغطية محفظة'],
            ['name_en' => 'Flip Cases', 'name_ar' => 'أغطية قابلة للطي'],
            ['name_en' => 'Bumper Cases', 'name_ar' => 'أغطية الحواف'],
        ];

        foreach ($caseTypes as $caseType) {
            Category::create([
                'name_en' => $caseType['name_en'],
                'name_ar' => $caseType['name_ar'],
                'parent_id' => $casesCategory->id,
            ]);
        }

        // Screen Protectors
        $screenProtectorCategory = Category::create([
            'name_en' => 'Screen Protectors',
            'name_ar' => 'حاميات الشاشة',
            'parent_id' => $accessoriesCategory->id,
        ]);

        $screenProtectorTypes = [
            ['name_en' => 'Tempered Glass', 'name_ar' => 'زجاج مقوى'],
            ['name_en' => 'Plastic Film', 'name_ar' => 'فيلم بلاستيكي'],
            ['name_en' => 'Privacy Screen', 'name_ar' => 'حامي الخصوصية'],
            ['name_en' => 'Blue Light Filter', 'name_ar' => 'فلتر الضوء الأزرق'],
            ['name_en' => 'Anti-Glare', 'name_ar' => 'مضاد للانعكاس'],
        ];

        foreach ($screenProtectorTypes as $protectorType) {
            Category::create([
                'name_en' => $protectorType['name_en'],
                'name_ar' => $protectorType['name_ar'],
                'parent_id' => $screenProtectorCategory->id,
            ]);
        }

        // Chargers & Cables
        $chargersCategory = Category::create([
            'name_en' => 'Chargers & Cables',
            'name_ar' => 'شواحن وكابلات',
            'parent_id' => $accessoriesCategory->id,
        ]);

        $chargerTypes = [
            ['name_en' => 'USB-C Cables', 'name_ar' => 'كابلات USB-C'],
            ['name_en' => 'Lightning Cables', 'name_ar' => 'كابلات Lightning'],
            ['name_en' => 'Micro USB Cables', 'name_ar' => 'كابلات Micro USB'],
            ['name_en' => 'Wireless Chargers', 'name_ar' => 'شواحن لاسلكية'],
            ['name_en' => 'Fast Chargers', 'name_ar' => 'شواحن سريعة'],
            ['name_en' => 'Car Chargers', 'name_ar' => 'شواحن السيارة'],
            ['name_en' => 'Power Adapters', 'name_ar' => 'محولات الطاقة'],
            ['name_en' => 'Cable Organizers', 'name_ar' => 'منظمات الكابلات'],
        ];

        foreach ($chargerTypes as $chargerType) {
            Category::create([
                'name_en' => $chargerType['name_en'],
                'name_ar' => $chargerType['name_ar'],
                'parent_id' => $chargersCategory->id,
            ]);
        }

        // Power Banks
        $powerBankCategory = Category::create([
            'name_en' => 'Power Banks',
            'name_ar' => 'بنوك الطاقة',
            'parent_id' => $accessoriesCategory->id,
        ]);

        $powerBankTypes = [
            ['name_en' => '10000mAh Power Banks', 'name_ar' => 'بنوك طاقة 10000 مللي أمبير'],
            ['name_en' => '20000mAh Power Banks', 'name_ar' => 'بنوك طاقة 20000 مللي أمبير'],
            ['name_en' => 'Wireless Power Banks', 'name_ar' => 'بنوك طاقة لاسلكية'],
            ['name_en' => 'Solar Power Banks', 'name_ar' => 'بنوك طاقة شمسية'],
            ['name_en' => 'Fast Charging Power Banks', 'name_ar' => 'بنوك طاقة شحن سريع'],
        ];

        foreach ($powerBankTypes as $powerBankType) {
            Category::create([
                'name_en' => $powerBankType['name_en'],
                'name_ar' => $powerBankType['name_ar'],
                'parent_id' => $powerBankCategory->id,
            ]);
        }

        // Headphones & Earphones
        $audioCategory = Category::create([
            'name_en' => 'Headphones & Earphones',
            'name_ar' => 'سماعات الرأس والأذن',
            'parent_id' => $accessoriesCategory->id,
        ]);

        $audioTypes = [
            ['name_en' => 'Wireless Earbuds', 'name_ar' => 'سماعات لاسلكية'],
            ['name_en' => 'Wired Earphones', 'name_ar' => 'سماعات سلكية'],
            ['name_en' => 'Over-Ear Headphones', 'name_ar' => 'سماعات رأس'],
            ['name_en' => 'On-Ear Headphones', 'name_ar' => 'سماعات أذن'],
            ['name_en' => 'Gaming Headsets', 'name_ar' => 'سماعات ألعاب'],
            ['name_en' => 'Sports Earphones', 'name_ar' => 'سماعات رياضية'],
            ['name_en' => 'Noise Cancelling', 'name_ar' => 'سماعات عازلة للضوضاء'],
        ];

        foreach ($audioTypes as $audioType) {
            Category::create([
                'name_en' => $audioType['name_en'],
                'name_ar' => $audioType['name_ar'],
                'parent_id' => $audioCategory->id,
            ]);
        }

        // Mobile Stands & Holders
        $standsCategory = Category::create([
            'name_en' => 'Mobile Stands & Holders',
            'name_ar' => 'حاملات الهواتف',
            'parent_id' => $accessoriesCategory->id,
        ]);

        $standTypes = [
            ['name_en' => 'Desk Stands', 'name_ar' => 'حاملات المكتب'],
            ['name_en' => 'Car Mounts', 'name_ar' => 'حاملات السيارة'],
            ['name_ar' => 'حاملات السرير', 'name_en' => 'Bed Stands'],
            ['name_en' => 'Flexible Stands', 'name_ar' => 'حاملات مرنة'],
            ['name_en' => 'Magnetic Mounts', 'name_ar' => 'حاملات مغناطيسية'],
            ['name_en' => 'Tripod Mounts', 'name_ar' => 'حاملات ثلاثية القوائم'],
        ];

        foreach ($standTypes as $standType) {
            Category::create([
                'name_en' => $standType['name_en'],
                'name_ar' => $standType['name_ar'],
                'parent_id' => $standsCategory->id,
            ]);
        }

        // Car Accessories
        $carAccessoriesCategory = Category::create([
            'name_en' => 'Car Accessories',
            'name_ar' => 'إكسسوارات السيارة',
            'parent_id' => $accessoriesCategory->id,
        ]);

        $carAccessoryTypes = [
            ['name_en' => 'Car Mounts', 'name_ar' => 'حاملات السيارة'],
            ['name_en' => 'Car Chargers', 'name_ar' => 'شواحن السيارة'],
            ['name_en' => 'Bluetooth Car Kits', 'name_ar' => 'أجهزة البلوتوث للسيارة'],
            ['name_en' => 'FM Transmitters', 'name_ar' => 'أجهزة إرسال FM'],
            ['name_en' => 'Car Phone Holders', 'name_ar' => 'حاملات هواتف السيارة'],
        ];

        foreach ($carAccessoryTypes as $carAccessoryType) {
            Category::create([
                'name_en' => $carAccessoryType['name_en'],
                'name_ar' => $carAccessoryType['name_ar'],
                'parent_id' => $carAccessoriesCategory->id,
            ]);
        }

        // Mobile Repair Tools
        $repairToolsCategory = Category::create([
            'name_en' => 'Mobile Repair Tools',
            'name_ar' => 'أدوات إصلاح الهواتف',
            'parent_id' => $accessoriesCategory->id,
        ]);

        $repairToolTypes = [
            ['name_en' => 'Screwdriver Sets', 'name_ar' => 'مجموعات مفكات'],
            ['name_en' => 'Opening Tools', 'name_ar' => 'أدوات الفتح'],
            ['name_en' => 'Replacement Screens', 'name_ar' => 'شاشات بديلة'],
            ['name_en' => 'Replacement Batteries', 'name_ar' => 'بطاريات بديلة'],
            ['name_en' => 'Adhesive Strips', 'name_ar' => 'شرائط لاصقة'],
            ['name_en' => 'Heat Guns', 'name_ar' => 'مسدسات حرارية'],
        ];

        foreach ($repairToolTypes as $repairToolType) {
            Category::create([
                'name_en' => $repairToolType['name_en'],
                'name_ar' => $repairToolType['name_ar'],
                'parent_id' => $repairToolsCategory->id,
            ]);
        }

        // Additional main categories for mobile phone stores
        $additionalCategories = [
            [
                'name_en' => 'Tablets',
                'name_ar' => 'أجهزة لوحية',
                'parent_id' => null,
            ],
            [
                'name_en' => 'Wearables',
                'name_ar' => 'الأجهزة القابلة للارتداء',
                'parent_id' => null,
            ],
            [
                'name_en' => 'Gaming',
                'name_ar' => 'ألعاب',
                'parent_id' => null,
            ],
            [
                'name_en' => 'Audio & Video',
                'name_ar' => 'الصوت والفيديو',
                'parent_id' => null,
            ],
            [
                'name_en' => 'Smart Home',
                'name_ar' => 'المنزل الذكي',
                'parent_id' => null,
            ],
            [
                'name_en' => 'Computer Accessories',
                'name_ar' => 'إكسسوارات الكمبيوتر',
                'parent_id' => null,
            ],
            [
                'name_en' => 'Photography',
                'name_ar' => 'التصوير',
                'parent_id' => null,
            ],
            [
                'name_en' => 'Health & Fitness',
                'name_ar' => 'الصحة واللياقة',
                'parent_id' => null,
            ],
        ];

        foreach ($additionalCategories as $category) {
            Category::create($category);
        }

        // Tablets subcategories
        $tabletsCategory = Category::where('name_ar', 'أجهزة لوحية')->first();
        if ($tabletsCategory) {
            $tabletBrands = [
                ['name_en' => 'iPad', 'name_ar' => 'آيباد'],
                ['name_en' => 'Samsung Galaxy Tab', 'name_ar' => 'سامسونج جالاكسي تاب'],
                ['name_en' => 'Huawei MediaPad', 'name_ar' => 'هواوي ميديا باد'],
                ['name_en' => 'Lenovo Tab', 'name_ar' => 'لينوفو تاب'],
                ['name_en' => 'Amazon Fire', 'name_ar' => 'أمازون فاير'],
            ];

            foreach ($tabletBrands as $tabletBrand) {
                Category::create([
                    'name_en' => $tabletBrand['name_en'],
                    'name_ar' => $tabletBrand['name_ar'],
                    'parent_id' => $tabletsCategory->id,
                ]);
            }
        }

        // Wearables subcategories
        $wearablesCategory = Category::where('name_ar', 'الأجهزة القابلة للارتداء')->first();
        if ($wearablesCategory) {
            $wearableTypes = [
                ['name_en' => 'Smart Watches', 'name_ar' => 'ساعات ذكية'],
                ['name_en' => 'Fitness Trackers', 'name_ar' => 'أجهزة تتبع اللياقة'],
                ['name_en' => 'Smart Bands', 'name_ar' => 'أساور ذكية'],
                ['name_en' => 'VR Headsets', 'name_ar' => 'نظارات الواقع الافتراضي'],
                ['name_en' => 'AR Glasses', 'name_ar' => 'نظارات الواقع المعزز'],
            ];

            foreach ($wearableTypes as $wearableType) {
                Category::create([
                    'name_en' => $wearableType['name_en'],
                    'name_ar' => $wearableType['name_ar'],
                    'parent_id' => $wearablesCategory->id,
                ]);
            }
        }

        // Gaming subcategories
        $gamingCategory = Category::where('name_ar', 'ألعاب')->first();
        if ($gamingCategory) {
            $gamingTypes = [
                ['name_en' => 'Mobile Games', 'name_ar' => 'ألعاب الهاتف'],
                ['name_en' => 'Gaming Controllers', 'name_ar' => 'أجهزة تحكم الألعاب'],
                ['name_en' => 'Gaming Headsets', 'name_ar' => 'سماعات الألعاب'],
                ['name_en' => 'Gaming Chairs', 'name_ar' => 'كراسي الألعاب'],
                ['name_en' => 'Gaming Keyboards', 'name_ar' => 'لوحات مفاتيح الألعاب'],
                ['name_en' => 'Gaming Mice', 'name_ar' => 'فأرات الألعاب'],
            ];

            foreach ($gamingTypes as $gamingType) {
                Category::create([
                    'name_en' => $gamingType['name_en'],
                    'name_ar' => $gamingType['name_ar'],
                    'parent_id' => $gamingCategory->id,
                ]);
            }
        }
    }
}
