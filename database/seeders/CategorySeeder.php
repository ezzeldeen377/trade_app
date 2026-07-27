<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    private int $count = 0;
    private string $now;

    public function run(): void
    {
        $this->now = now()->toDateTimeString();
        $categories = $this->getCategories();

        foreach ($categories as $data) {
            $this->createCategory($data, position: 0, parentId: 0);
        }

        $this->command->info("Seeded {$this->count} categories with translations (EN + AR) and images.");
    }

    private function createCategory(array $data, int $position, int $parentId): int
    {
        $icon = null;
        if (isset($data['image_url'])) {
            $icon = $this->downloadImage($data['image_url'], $data['slug'] . '.webp');
        }

        $categoryId = DB::table('categories')->insertGetId([
            'name'              => $data['name_en'],
            'slug'              => $data['slug'],
            'icon'              => $icon,
            'icon_storage_type' => $icon ? 'public' : null,
            'parent_id'         => $parentId,
            'position'          => $position,
            'home_status'       => 1,
            'priority'          => $data['priority'] ?? 0,
            'created_at'        => $this->now,
            'updated_at'        => $this->now,
        ]);

        DB::table('translations')->insert([
            'translationable_type' => 'App\Models\Category',
            'translationable_id'   => $categoryId,
            'locale'               => 'en',
            'key'                  => 'name',
            'value'                => $data['name_en'],
        ]);

        DB::table('translations')->insert([
            'translationable_type' => 'App\Models\Category',
            'translationable_id'   => $categoryId,
            'locale'               => 'ar',
            'key'                  => 'name',
            'value'                => $data['name_ar'],
        ]);

        $this->count++;

        if (isset($data['sub_categories'])) {
            foreach ($data['sub_categories'] as $sub) {
                $this->createCategory($sub, position: $position + 1, parentId: $categoryId);
            }
        }

        return $categoryId;
    }

    private function downloadImage(string $url, string $filename): ?string
    {
        try {
            $path = 'category/' . $filename;
            $fullPath = storage_path('app/public/' . $path);

            if (Storage::disk('public')->exists($path)) {
                return $filename;
            }

            $response = Http::timeout(15)->get($url);

            if ($response->successful()) {
                Storage::disk('public')->put($path, $response->body());
                return $filename;
            }
        } catch (\Exception $e) {
            $this->command->warn("Failed to download {$url}: {$e->getMessage()}");
        }

        return null;
    }

    private function getCategories(): array
    {
        $baseUrl = 'https://cdn2.scenesku.com/resources';

        return [
            [
                'name_en'    => 'Electronics',
                'name_ar'    => 'إلكترونيات',
                'slug'       => 'electronics',
                'image_url'  => "$baseUrl/electronics.png",
                'priority'   => 10,
                'sub_categories' => [
                    [
                        'name_en'    => 'Mobile Phones',
                        'name_ar'    => 'هواتف محمولة',
                        'slug'       => 'mobile-phones',
                        'priority'   => 10,
                        'sub_categories' => [
                            ['name_en' => 'Smartphones',       'name_ar' => 'هواتف ذكية',           'slug' => 'smartphones',       'priority' => 0],
                            ['name_en' => 'Feature Phones',    'name_ar' => 'هواتف عادية',           'slug' => 'feature-phones',    'priority' => 0],
                            ['name_en' => 'Phone Accessories', 'name_ar' => 'ملحقات الهواتف',         'slug' => 'phone-accessories', 'priority' => 0],
                        ],
                    ],
                    [
                        'name_en'    => 'Laptops & Computers',
                        'name_ar'    => 'أجهزة كمبيوتر محمولة وكمبيوترات',
                        'slug'       => 'laptops-computers',
                        'image_url'  => "$baseUrl/computers-audio.png",
                        'priority'   => 9,
                        'sub_categories' => [
                            ['name_en' => 'Laptops',              'name_ar' => 'أجهزة كمبيوتر محمولة',  'slug' => 'laptops',              'priority' => 0],
                            ['name_en' => 'Desktops',             'name_ar' => 'كمبيوتر مكتبي',         'slug' => 'desktops',             'priority' => 0],
                            ['name_en' => 'Computer Accessories', 'name_ar' => 'ملحقات الكمبيوتر',       'slug' => 'computer-accessories', 'priority' => 0],
                            ['name_en' => 'Monitors',             'name_ar' => 'شاشات عرض',             'slug' => 'monitors',             'priority' => 0],
                        ],
                    ],
                    [
                        'name_en'    => 'Audio & Video',
                        'name_ar'    => 'صوت وفيديو',
                        'slug'       => 'audio-video',
                        'priority'   => 8,
                        'sub_categories' => [
                            ['name_en' => 'Headphones',  'name_ar' => 'سماعات',          'slug' => 'headphones',  'priority' => 0],
                            ['name_en' => 'Speakers',    'name_ar' => 'مكبرات صوت',      'slug' => 'speakers',    'priority' => 0],
                            ['name_en' => 'Home Theater','name_ar' => 'مسرح منزلي',      'slug' => 'home-theater','priority' => 0],
                        ],
                    ],
                    [
                        'name_en'    => 'Cameras',
                        'name_ar'    => 'كاميرات',
                        'slug'       => 'cameras',
                        'image_url'  => "$baseUrl/cameras.png",
                        'priority'   => 7,
                        'sub_categories' => [
                            ['name_en' => 'DSLR Cameras',       'name_ar' => 'كاميرات رقمية انعكاسية', 'slug' => 'dslr-cameras',       'priority' => 0],
                            ['name_en' => 'Mirrorless Cameras', 'name_ar' => 'كاميرات بدون مرآة',      'slug' => 'mirrorless-cameras', 'priority' => 0],
                            ['name_en' => 'Action Cameras',     'name_ar' => 'كاميرات حركة',           'slug' => 'action-cameras',     'priority' => 0],
                            ['name_en' => 'Camera Accessories', 'name_ar' => 'ملحقات الكاميرات',       'slug' => 'camera-accessories', 'priority' => 0],
                        ],
                    ],
                ],
            ],

            [
                'name_en'    => 'Fashion',
                'name_ar'    => 'أزياء',
                'slug'       => 'fashion',
                'image_url'  => "$baseUrl/womens-clothing.png",
                'priority'   => 9,
                'sub_categories' => [
                    [
                        'name_en'    => "Men's Clothing",
                        'name_ar'    => 'ملابس رجالية',
                        'slug'       => 'mens-clothing',
                        'image_url'  => "$baseUrl/mens-clothing.png",
                        'priority'   => 10,
                        'sub_categories' => [
                            ['name_en' => 'T-Shirts', 'name_ar' => 'تيشيرتات', 'slug' => 'mens-tshirts', 'priority' => 0],
                            ['name_en' => 'Shirts',   'name_ar' => 'قمصان',    'slug' => 'mens-shirts',  'priority' => 0],
                            ['name_en' => 'Pants',    'name_ar' => 'سراويل',   'slug' => 'mens-pants',   'priority' => 0],
                            ['name_en' => 'Jackets',  'name_ar' => 'سترات',    'slug' => 'mens-jackets', 'priority' => 0],
                        ],
                    ],
                    [
                        'name_en'    => "Women's Clothing",
                        'name_ar'    => 'ملابس نسائية',
                        'slug'       => 'womens-clothing',
                        'priority'   => 9,
                        'sub_categories' => [
                            ['name_en' => 'Dresses',   'name_ar' => 'فساتين',   'slug' => 'womens-dresses',   'priority' => 0],
                            ['name_en' => 'Tops',      'name_ar' => 'بلوزات',   'slug' => 'womens-tops',      'priority' => 0],
                            ['name_en' => 'Skirts',    'name_ar' => 'تنانير',   'slug' => 'womens-skirts',    'priority' => 0],
                            ['name_en' => 'Outerwear', 'name_ar' => 'أزياء خارجية', 'slug' => 'womens-outerwear', 'priority' => 0],
                        ],
                    ],
                    [
                        'name_en'    => 'Shoes',
                        'name_ar'    => 'أحذية',
                        'slug'       => 'shoes',
                        'image_url'  => "$baseUrl/shoes.png",
                        'priority'   => 8,
                        'sub_categories' => [
                            ['name_en' => 'Sneakers',     'name_ar' => 'أحذية رياضية',  'slug' => 'sneakers',     'priority' => 0],
                            ['name_en' => 'Formal Shoes', 'name_ar' => 'أحذية رسمية',   'slug' => 'formal-shoes', 'priority' => 0],
                            ['name_en' => 'Sandals',      'name_ar' => 'صندل',          'slug' => 'sandals',      'priority' => 0],
                            ['name_en' => 'Boots',        'name_ar' => 'بوت',           'slug' => 'boots',        'priority' => 0],
                        ],
                    ],
                    [
                        'name_en'    => 'Accessories',
                        'name_ar'    => 'إكسسوارات',
                        'slug'       => 'accessories',
                        'image_url'  => "$baseUrl/jewelry-accessories.png",
                        'priority'   => 7,
                        'sub_categories' => [
                            ['name_en' => 'Bags',       'name_ar' => 'حقائب',    'slug' => 'bags',       'priority' => 0],
                            ['name_en' => 'Watches',    'name_ar' => 'ساعات',    'slug' => 'watches',    'priority' => 0],
                            ['name_en' => 'Jewelry',    'name_ar' => 'مجوهرات',  'slug' => 'jewelry',    'priority' => 0],
                            ['name_en' => 'Sunglasses', 'name_ar' => 'نظارات شمسية', 'slug' => 'sunglasses', 'priority' => 0],
                        ],
                    ],
                ],
            ],

            [
                'name_en'    => 'Home & Garden',
                'name_ar'    => 'المنزل والحديقة',
                'slug'       => 'home-garden',
                'image_url'  => "$baseUrl/furniture.png",
                'priority'   => 8,
                'sub_categories' => [
                    [
                        'name_en'    => 'Furniture',
                        'name_ar'    => 'أثاث',
                        'slug'       => 'furniture',
                        'priority'   => 10,
                        'sub_categories' => [
                            ['name_en' => 'Sofas',  'name_ar' => 'أريكة',  'slug' => 'sofas',  'priority' => 0],
                            ['name_en' => 'Tables', 'name_ar' => 'طاولات', 'slug' => 'tables', 'priority' => 0],
                            ['name_en' => 'Chairs', 'name_ar' => 'كراسي',  'slug' => 'chairs', 'priority' => 0],
                            ['name_en' => 'Beds',   'name_ar' => 'أسرّة',   'slug' => 'beds',   'priority' => 0],
                        ],
                    ],
                    [
                        'name_en'    => 'Kitchen',
                        'name_ar'    => 'المطبخ',
                        'slug'       => 'kitchen',
                        'image_url'  => "$baseUrl/kitchen-dining.png",
                        'priority'   => 9,
                        'sub_categories' => [
                            ['name_en' => 'Cookware',   'name_ar' => 'أدوات طبخ',     'slug' => 'cookware',   'priority' => 0],
                            ['name_en' => 'Utensils',   'name_ar' => 'أدوات مطبخ',     'slug' => 'utensils',   'priority' => 0],
                            ['name_en' => 'Appliances', 'name_ar' => 'أجهزة منزلية',   'slug' => 'appliances', 'priority' => 0],
                        ],
                    ],
                    [
                        'name_en'    => 'Garden',
                        'name_ar'    => 'الحديقة',
                        'slug'       => 'garden',
                        'image_url'  => "$baseUrl/garden-outdoor.png",
                        'priority'   => 8,
                        'sub_categories' => [
                            ['name_en' => 'Plants',            'name_ar' => 'نباتات',         'slug' => 'plants',            'priority' => 0],
                            ['name_en' => 'Tools',             'name_ar' => 'أدوات حديقة',    'slug' => 'garden-tools',      'priority' => 0],
                            ['name_en' => 'Outdoor Furniture', 'name_ar' => 'أثاث خارجي',     'slug' => 'outdoor-furniture', 'priority' => 0],
                        ],
                    ],
                ],
            ],

            [
                'name_en'    => 'Sports & Outdoors',
                'name_ar'    => 'الرياضة والهواء الطلق',
                'slug'       => 'sports-outdoors',
                'image_url'  => "$baseUrl/sports-equipment.png",
                'priority'   => 7,
                'sub_categories' => [
                    [
                        'name_en'    => 'Fitness',
                        'name_ar'    => 'اللياقة البدنية',
                        'slug'       => 'fitness',
                        'image_url'  => "$baseUrl/fitness.png",
                        'priority'   => 10,
                        'sub_categories' => [
                            ['name_en' => 'Gym Equipment', 'name_ar' => 'أجهزة جيم',  'slug' => 'gym-equipment', 'priority' => 0],
                            ['name_en' => 'Yoga Mats',     'name_ar' => 'سجاد يوغا',  'slug' => 'yoga-mats',     'priority' => 0],
                            ['name_en' => 'Dumbbells',     'name_ar' => 'دمبلز',      'slug' => 'dumbbells',     'priority' => 0],
                        ],
                    ],
                    [
                        'name_en'    => 'Outdoor Sports',
                        'name_ar'    => 'رياضات خارجية',
                        'slug'       => 'outdoor-sports',
                        'priority'   => 9,
                        'sub_categories' => [
                            ['name_en' => 'Cycling',  'name_ar' => 'ركوب الدراجات', 'slug' => 'cycling',  'priority' => 0],
                            ['name_en' => 'Hiking',   'name_ar' => 'المشي لمسافات طويلة', 'slug' => 'hiking', 'priority' => 0],
                            ['name_en' => 'Camping',  'name_ar' => 'التخييم',       'slug' => 'camping',  'priority' => 0],
                        ],
                    ],
                    [
                        'name_en'    => 'Team Sports',
                        'name_ar'    => 'رياضات جماعية',
                        'slug'       => 'team-sports',
                        'priority'   => 8,
                        'sub_categories' => [
                            ['name_en' => 'Football',    'name_ar' => 'كرة قدم',    'slug' => 'football',    'priority' => 0],
                            ['name_en' => 'Basketball',  'name_ar' => 'كرة سلة',    'slug' => 'basketball',  'priority' => 0],
                            ['name_en' => 'Tennis',      'name_ar' => 'تنس',        'slug' => 'tennis',      'priority' => 0],
                        ],
                    ],
                ],
            ],

            [
                'name_en'    => 'Beauty & Health',
                'name_ar'    => 'الجمال والصحة',
                'slug'       => 'beauty-health',
                'image_url'  => "$baseUrl/beauty-makeup.png",
                'priority'   => 6,
                'sub_categories' => [
                    [
                        'name_en'    => 'Skincare',
                        'name_ar'    => 'العناية بالبشرة',
                        'slug'       => 'skincare',
                        'image_url'  => "$baseUrl/skincare.png",
                        'priority'   => 10,
                        'sub_categories' => [
                            ['name_en' => 'Moisturizers', 'name_ar' => 'مرطبات',    'slug' => 'moisturizers', 'priority' => 0],
                            ['name_en' => 'Cleansers',    'name_ar' => 'منظفات',    'slug' => 'cleansers',    'priority' => 0],
                            ['name_en' => 'Sunscreen',    'name_ar' => 'واقي شمس',  'slug' => 'sunscreen',    'priority' => 0],
                        ],
                    ],
                    [
                        'name_en'    => 'Makeup',
                        'name_ar'    => 'مكياج',
                        'slug'       => 'makeup',
                        'priority'   => 9,
                        'sub_categories' => [
                            ['name_en' => 'Foundation', 'name_ar' => 'كريم أساس',  'slug' => 'foundation', 'priority' => 0],
                            ['name_en' => 'Lipstick',   'name_ar' => 'أحمر شفاه', 'slug' => 'lipstick',   'priority' => 0],
                            ['name_en' => 'Eye Makeup', 'name_ar' => 'مكياج عيون', 'slug' => 'eye-makeup', 'priority' => 0],
                        ],
                    ],
                    [
                        'name_en'    => 'Hair Care',
                        'name_ar'    => 'العناية بالشعر',
                        'slug'       => 'hair-care',
                        'image_url'  => "$baseUrl/hair-care.png",
                        'priority'   => 8,
                        'sub_categories' => [
                            ['name_en' => 'Shampoo',     'name_ar' => 'شامبو',       'slug' => 'shampoo',     'priority' => 0],
                            ['name_en' => 'Conditioner', 'name_ar' => 'بلسم',        'slug' => 'conditioner', 'priority' => 0],
                            ['name_en' => 'Hair Styling','name_ar' => 'تصفيف شعر',   'slug' => 'hair-styling','priority' => 0],
                        ],
                    ],
                ],
            ],

            [
                'name_en'    => 'Books & Stationery',
                'name_ar'    => 'كتب وقرطاسية',
                'slug'       => 'books-stationery',
                'image_url'  => "$baseUrl/school-supplies.png",
                'priority'   => 5,
                'sub_categories' => [
                    [
                        'name_en'    => 'Books',
                        'name_ar'    => 'كتب',
                        'slug'       => 'books',
                        'priority'   => 10,
                        'sub_categories' => [
                            ['name_en' => 'Fiction',     'name_ar' => 'روايات',       'slug' => 'fiction',     'priority' => 0],
                            ['name_en' => 'Non-Fiction', 'name_ar' => 'كتب غير خيالية', 'slug' => 'non-fiction', 'priority' => 0],
                            ['name_en' => 'Academic',    'name_ar' => 'كتب أكاديمية', 'slug' => 'academic',    'priority' => 0],
                        ],
                    ],
                    [
                        'name_en'    => 'Stationery',
                        'name_ar'    => 'قرطاسية',
                        'slug'       => 'stationery',
                        'priority'   => 9,
                        'sub_categories' => [
                            ['name_en' => 'Pens & Pencils', 'name_ar' => 'أقلام',      'slug' => 'pens-pencils', 'priority' => 0],
                            ['name_en' => 'Notebooks',      'name_ar' => 'دفاتر',      'slug' => 'notebooks',    'priority' => 0],
                            ['name_en' => 'Art Supplies',   'name_ar' => 'مستلزمات فنية', 'slug' => 'art-supplies', 'priority' => 0],
                        ],
                    ],
                ],
            ],

            [
                'name_en'    => 'Toys & Kids',
                'name_ar'    => 'ألعاب وأطفال',
                'slug'       => 'toys-kids',
                'image_url'  => "$baseUrl/toys-games.png",
                'priority'   => 4,
                'sub_categories' => [
                    [
                        'name_en'    => 'Toys',
                        'name_ar'    => 'ألعاب',
                        'slug'       => 'toys',
                        'priority'   => 10,
                        'sub_categories' => [
                            ['name_en' => 'Action Figures',  'name_ar' => 'دمى حركية',     'slug' => 'action-figures',  'priority' => 0],
                            ['name_en' => 'Board Games',     'name_ar' => 'ألعاب لوحية',   'slug' => 'board-games',     'priority' => 0],
                            ['name_en' => 'Building Blocks', 'name_ar' => 'مكعبات بناء',   'slug' => 'building-blocks', 'priority' => 0],
                        ],
                    ],
                    [
                        'name_en'    => 'Baby Products',
                        'name_ar'    => 'منتجات أطفال',
                        'slug'       => 'baby-products',
                        'image_url'  => "$baseUrl/baby-products.png",
                        'priority'   => 9,
                        'sub_categories' => [
                            ['name_en' => 'Diapers',  'name_ar' => 'حفاضات',  'slug' => 'diapers',  'priority' => 0],
                            ['name_en' => 'Feeding',  'name_ar' => 'رضاعة',   'slug' => 'feeding',  'priority' => 0],
                            ['name_en' => 'Strollers','name_ar' => 'عربات أطفال', 'slug' => 'strollers','priority' => 0],
                        ],
                    ],
                ],
            ],

            [
                'name_en'    => 'Automotive',
                'name_ar'    => 'سيارات',
                'slug'       => 'automotive',
                'image_url'  => "$baseUrl/car-accessories.png",
                'priority'   => 3,
                'sub_categories' => [
                    [
                        'name_en'    => 'Car Accessories',
                        'name_ar'    => 'ملحقات السيارات',
                        'slug'       => 'car-accessories',
                        'priority'   => 10,
                        'sub_categories' => [
                            ['name_en' => 'Seat Covers',     'name_ar' => 'غطاء مقاعد',     'slug' => 'seat-covers',     'priority' => 0],
                            ['name_en' => 'Car Electronics', 'name_ar' => 'إلكترونيات السيارة', 'slug' => 'car-electronics', 'priority' => 0],
                            ['name_en' => 'Floor Mats',      'name_ar' => 'سجاد السيارة',   'slug' => 'floor-mats',      'priority' => 0],
                        ],
                    ],
                    [
                        'name_en'    => 'Spare Parts',
                        'name_ar'    => 'قطع غيار',
                        'slug'       => 'spare-parts',
                        'priority'   => 9,
                        'sub_categories' => [
                            ['name_en' => 'Engine Parts', 'name_ar' => 'قطع محرك',    'slug' => 'engine-parts', 'priority' => 0],
                            ['name_en' => 'Brake Parts',  'name_ar' => 'قطع فرامل',   'slug' => 'brake-parts',  'priority' => 0],
                            ['name_en' => 'Filters',      'name_ar' => 'فلاتر',       'slug' => 'filters',      'priority' => 0],
                        ],
                    ],
                ],
            ],
        ];
    }
}
