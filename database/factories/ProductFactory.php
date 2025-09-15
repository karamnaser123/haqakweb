<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get random subcategory
        $subcategory = Category::whereNotNull('parent_id')->inRandomOrder()->first();

        // Get store user
        $store = User::where('email', 'store@example.com')->first();

        // Product names in Arabic and English
        $productNames = [
            ['en' => 'iPhone 15 Pro Max 256GB', 'ar' => 'آيفون 15 برو ماكس 256 جيجابايت'],
            ['en' => 'Samsung Galaxy S24 Ultra 512GB', 'ar' => 'سامسونج جالاكسي S24 ألترا 512 جيجابايت'],
            ['en' => 'Xiaomi Redmi Note 13 Pro', 'ar' => 'شاومي ريدمي نوت 13 برو'],
            ['en' => 'OnePlus 12 256GB', 'ar' => 'وان بلس 12 256 جيجابايت'],
            ['en' => 'Huawei P60 Pro 512GB', 'ar' => 'هواوي P60 برو 512 جيجابايت'],
            ['en' => 'Oppo Find X7 Ultra', 'ar' => 'أوبو فايند X7 ألترا'],
            ['en' => 'Vivo X100 Pro', 'ar' => 'فيفو X100 برو'],
            ['en' => 'Realme GT5 Pro', 'ar' => 'ريلمي GT5 برو'],
            ['en' => 'Honor Magic6 Pro', 'ar' => 'هونر ماجيك 6 برو'],
            ['en' => 'Nokia X30 5G', 'ar' => 'نوكيا X30 5G'],
            ['en' => 'Motorola Edge 40 Pro', 'ar' => 'موتورولا إيدج 40 برو'],
            ['en' => 'Sony Xperia 1 V', 'ar' => 'سوني إكسبيريا 1 V'],
            ['en' => 'iPhone 15 Pro Silicone Case - Blue', 'ar' => 'غطاء سيليكون آيفون 15 برو - أزرق'],
            ['en' => 'Samsung Galaxy S24 Ultra Clear Case', 'ar' => 'غطاء شفاف سامسونج جالاكسي S24 ألترا'],
            ['en' => 'Leather Phone Case - Black', 'ar' => 'غطاء جلد للهاتف - أسود'],
            ['en' => 'Rugged Phone Case - Army Green', 'ar' => 'غطاء مقاوم للصدمات - أخضر عسكري'],
            ['en' => 'Wallet Phone Case - Brown', 'ar' => 'غطاء محفظة للهاتف - بني'],
            ['en' => 'Flip Phone Case - Red', 'ar' => 'غطاء قابل للطي - أحمر'],
            ['en' => 'Tempered Glass Screen Protector', 'ar' => 'حامي شاشة زجاج مقوى'],
            ['en' => 'Privacy Screen Protector', 'ar' => 'حامي شاشة خصوصية'],
            ['en' => 'Blue Light Filter Screen Protector', 'ar' => 'حامي شاشة فلتر الضوء الأزرق'],
            ['en' => 'Anti-Glare Screen Protector', 'ar' => 'حامي شاشة مضاد للانعكاس'],
            ['en' => 'Wireless Charging Pad 15W', 'ar' => 'منصة شحن لاسلكي 15 واط'],
            ['en' => 'USB-C Fast Charger 65W', 'ar' => 'شاحن USB-C سريع 65 واط'],
            ['en' => 'Lightning Cable 2m', 'ar' => 'كابل Lightning 2 متر'],
            ['en' => 'Micro USB Cable 1.5m', 'ar' => 'كابل Micro USB 1.5 متر'],
            ['en' => 'Car Charger Dual Port', 'ar' => 'شاحن سيارة منفذين'],
            ['en' => 'Power Adapter 30W', 'ar' => 'محول طاقة 30 واط'],
            ['en' => 'Cable Organizer Box', 'ar' => 'صندوق تنظيم الكابلات'],
            ['en' => '20000mAh Power Bank', 'ar' => 'بنك طاقة 20000 مللي أمبير'],
            ['en' => '10000mAh Solar Power Bank', 'ar' => 'بنك طاقة شمسي 10000 مللي أمبير'],
            ['en' => 'Wireless Power Bank 5000mAh', 'ar' => 'بنك طاقة لاسلكي 5000 مللي أمبير'],
            ['en' => 'Fast Charging Power Bank 15000mAh', 'ar' => 'بنك طاقة شحن سريع 15000 مللي أمبير'],
            ['en' => 'AirPods Pro 2nd Generation', 'ar' => 'إيربودز برو الجيل الثاني'],
            ['en' => 'Sony WH-1000XM5 Headphones', 'ar' => 'سماعات سوني WH-1000XM5'],
            ['en' => 'Samsung Galaxy Buds2 Pro', 'ar' => 'سماعات سامسونج جالاكسي بودز 2 برو'],
            ['en' => 'JBL Charge 5 Speaker', 'ar' => 'سماعة JBL تشارج 5'],
            ['en' => 'Bose QuietComfort 45', 'ar' => 'سماعات بوز كوايت كومفورت 45'],
            ['en' => 'Sennheiser HD 660S', 'ar' => 'سماعات سينهايزر HD 660S'],
            ['en' => 'Gaming Headset RGB', 'ar' => 'سماعات ألعاب RGB'],
            ['en' => 'Sports Bluetooth Earphones', 'ar' => 'سماعات رياضية بلوتوث'],
            ['en' => 'iPad Air 5th Gen 256GB', 'ar' => 'آيباد إير الجيل الخامس 256 جيجابايت'],
            ['en' => 'Samsung Galaxy Tab S9 128GB', 'ar' => 'سامسونج جالاكسي تاب S9 128 جيجابايت'],
            ['en' => 'Huawei MediaPad Pro 12.6', 'ar' => 'هواوي ميديا باد برو 12.6'],
            ['en' => 'Lenovo Tab P11 Pro', 'ar' => 'لينوفو تاب P11 برو'],
            ['en' => 'Amazon Fire HD 10', 'ar' => 'أمازون فاير HD 10'],
            ['en' => 'Apple Watch Series 9 GPS 45mm', 'ar' => 'ساعة آبل ووتش سيريز 9 GPS 45 ملم'],
            ['en' => 'Samsung Galaxy Watch 6 Classic 47mm', 'ar' => 'ساعة سامسونج جالاكسي ووتش 6 كلاسيك 47 ملم'],
            ['en' => 'Huawei Watch GT 4', 'ar' => 'ساعة هواوي GT 4'],
            ['en' => 'Xiaomi Mi Band 8', 'ar' => 'سوار شاومي مي باند 8'],
            ['en' => 'Fitbit Charge 5', 'ar' => 'فيت بيت تشارج 5'],
            ['en' => 'Garmin Venu 3', 'ar' => 'جارمين فينو 3'],
            ['en' => 'VR Headset Oculus Quest 3', 'ar' => 'نظارات الواقع الافتراضي أوكولس كويست 3'],
            ['en' => 'AR Glasses Magic Leap 2', 'ar' => 'نظارات الواقع المعزز ماجيك ليب 2'],
            ['en' => 'Gaming Controller Xbox', 'ar' => 'جهاز تحكم ألعاب إكس بوكس'],
            ['en' => 'Gaming Controller PlayStation', 'ar' => 'جهاز تحكم ألعاب بلايستيشن'],
            ['en' => 'Gaming Keyboard Mechanical', 'ar' => 'لوحة مفاتيح ألعاب ميكانيكية'],
            ['en' => 'Gaming Mouse RGB', 'ar' => 'فأرة ألعاب RGB'],
            ['en' => 'Gaming Chair Ergonomic', 'ar' => 'كرسي ألعاب مريح'],
            ['en' => 'Gaming Monitor 27 inch', 'ar' => 'شاشة ألعاب 27 بوصة'],
            ['en' => 'Phone Cooler Fan', 'ar' => 'مروحة تبريد الهاتف'],
            ['en' => 'Car Phone Mount Magnetic', 'ar' => 'حامل هاتف مغناطيسي للسيارة'],
            ['en' => 'Car Phone Mount with Wireless Charging', 'ar' => 'حامل هاتف مع شحن لاسلكي للسيارة'],
            ['en' => 'Bluetooth Car Kit', 'ar' => 'جهاز بلوتوث للسيارة'],
            ['en' => 'FM Transmitter Car', 'ar' => 'جهاز إرسال FM للسيارة'],
            ['en' => 'Car Phone Holder Dashboard', 'ar' => 'حامل هاتف لوحة القيادة'],
            ['en' => 'Desk Phone Stand Adjustable', 'ar' => 'حامل هاتف مكتب قابل للتعديل'],
            ['en' => 'Bed Phone Stand Flexible', 'ar' => 'حامل هاتف سرير مرن'],
            ['en' => 'Tripod Phone Mount', 'ar' => 'حامل هاتف ثلاثي القوائم'],
            ['en' => 'Magnetic Phone Mount', 'ar' => 'حامل هاتف مغناطيسي'],
            ['en' => 'Flexible Phone Stand', 'ar' => 'حامل هاتف مرن'],
            ['en' => 'iPhone 15 Pro Max Camera Lens', 'ar' => 'عدسة كاميرا آيفون 15 برو ماكس'],
            ['en' => 'Samsung Galaxy S24 Ultra Camera Lens', 'ar' => 'عدسة كاميرا سامسونج جالاكسي S24 ألترا'],
            ['en' => 'Universal Camera Lens Kit', 'ar' => 'مجموعة عدسات كاميرا عالمية'],
            ['en' => 'Wide Angle Lens 0.6x', 'ar' => 'عدسة واسعة الزاوية 0.6x'],
            ['en' => 'Macro Lens 15x', 'ar' => 'عدسة ماكرو 15x'],
            ['en' => 'Telephoto Lens 2x', 'ar' => 'عدسة تيليفوتو 2x'],
            ['en' => 'Fisheye Lens 180°', 'ar' => 'عدسة عين السمكة 180°'],
            ['en' => 'Polarizing Filter', 'ar' => 'فلتر استقطاب'],
            ['en' => 'UV Filter', 'ar' => 'فلتر UV'],
            ['en' => 'ND Filter Variable', 'ar' => 'فلتر ND متغير'],
            ['en' => 'Smart Ring Fitness Tracker', 'ar' => 'خاتم ذكي لتتبع اللياقة'],
            ['en' => 'Smart Glasses AR', 'ar' => 'نظارات ذكية واقع معزز'],
            ['en' => 'Smart Band Health Monitor', 'ar' => 'سوار ذكي مراقب الصحة'],
            ['en' => 'Fitness Tracker Heart Rate', 'ar' => 'جهاز تتبع اللياقة معدل ضربات القلب'],
            ['en' => 'Sleep Tracker Smart', 'ar' => 'جهاز تتبع النوم الذكي'],
            ['en' => 'Blood Pressure Monitor', 'ar' => 'جهاز قياس ضغط الدم'],
            ['en' => 'Blood Oxygen Monitor', 'ar' => 'جهاز قياس الأكسجين في الدم'],
            ['en' => 'ECG Monitor Smart', 'ar' => 'جهاز تخطيط القلب الذكي'],
            ['en' => 'Body Fat Scale Smart', 'ar' => 'ميزان دهون الجسم الذكي'],
            ['en' => 'Smart Scale WiFi', 'ar' => 'ميزان ذكي واي فاي'],
            ['en' => 'Portable Bluetooth Speaker', 'ar' => 'سماعة بلوتوث محمولة'],
            ['en' => 'Waterproof Bluetooth Speaker', 'ar' => 'سماعة بلوتوث مقاومة للماء'],
            ['en' => 'Party Speaker 50W', 'ar' => 'سماعة حفلات 50 واط'],
            ['en' => 'Karaoke Microphone Bluetooth', 'ar' => 'ميكروفون كاريوكي بلوتوث'],
            ['en' => 'Wireless Microphone System', 'ar' => 'نظام ميكروفون لاسلكي'],
            ['en' => 'Audio Mixer 4 Channel', 'ar' => 'ميكسر صوتي 4 قنوات'],
            ['en' => 'Audio Interface USB', 'ar' => 'واجهة صوتية USB'],
            ['en' => 'Studio Headphones Professional', 'ar' => 'سماعات استوديو احترافية'],
            ['en' => 'Condenser Microphone USB', 'ar' => 'ميكروفون مكثف USB'],
            ['en' => 'Smart Home Hub WiFi', 'ar' => 'محور المنزل الذكي واي فاي'],
            ['en' => 'Smart Light Bulb RGB', 'ar' => 'لمبة ذكية RGB'],
            ['en' => 'Smart Switch WiFi', 'ar' => 'مفتاح ذكي واي فاي'],
            ['en' => 'Smart Door Lock Fingerprint', 'ar' => 'قفل باب ذكي بصمة إصبع'],
            ['en' => 'Smart Camera Security', 'ar' => 'كاميرا أمنية ذكية'],
            ['en' => 'Smart Doorbell Video', 'ar' => 'جرس باب ذكي فيديو'],
            ['en' => 'Smart Thermostat WiFi', 'ar' => 'منظم حرارة ذكي واي فاي'],
            ['en' => 'Smart Smoke Detector', 'ar' => 'كاشف دخان ذكي'],
            ['en' => 'Smart Water Leak Detector', 'ar' => 'كاشف تسرب مياه ذكي'],
            ['en' => 'Smart Motion Sensor', 'ar' => 'مستشعر حركة ذكي'],
        ];

        $randomProduct = $this->faker->randomElement($productNames);

        return [
            'store_id' => $store ? $store->id : 1,
            'category_id' => $subcategory ? $subcategory->id : 1,
            'name_en' => $randomProduct['en'],
            'name_ar' => $randomProduct['ar'],
            'price' => $this->faker->randomFloat(2, 1, 100),
            'discount' => $this->faker->randomFloat(2, 0, 100),
            'stock' => $this->faker->numberBetween(0, 200),
            'description_en' => $this->faker->sentence(15),
            'description_ar' => $this->faker->randomElement([
                'منتج عالي الجودة مع ضمان شامل',
                'أحدث التقنيات والأداء المتميز',
                'تصميم أنيق ومتانة عالية',
                'مواصفات تقنية متقدمة',
                'سعر مناسب وجودة ممتازة',
                'مناسب للاستخدام اليومي',
                'تقنية حديثة وأداء متميز',
                'جودة عالية وضمان شامل',
                'تصميم عصري ومواصفات متقدمة',
                'أداء متميز وسعر مناسب'
            ]),
            'active' => $this->faker->boolean(100), // 90% chance of being active
            'featured' => $this->faker->boolean(20), // 20% chance of being featured
            'new' => $this->faker->boolean(30), // 30% chance of being new
            'best_seller' => $this->faker->boolean(25), // 25% chance of being best seller
            'top_rated' => $this->faker->boolean(35), // 35% chance of being top rated
        ];
    }

    /**
     * Indicate that the product is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'featured' => true,
        ]);
    }

    /**
     * Indicate that the product is new.
     */
    public function isNew(): static
    {
        return $this->state(fn (array $attributes) => [
            'new' => true,
        ]);
    }

    /**
     * Indicate that the product is a best seller.
     */
    public function bestSeller(): static
    {
        return $this->state(fn (array $attributes) => [
            'best_seller' => true,
        ]);
    }

    /**
     * Indicate that the product is top rated.
     */
    public function topRated(): static
    {
        return $this->state(fn (array $attributes) => [
            'top_rated' => true,
        ]);
    }

    /**
     * Indicate that the product is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'active' => false,
        ]);
    }
}
