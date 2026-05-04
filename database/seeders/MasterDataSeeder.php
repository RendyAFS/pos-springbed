<?php

namespace Database\Seeders;

use App\Enums\TypeCourierEnum;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Courier;
use App\Models\Customer;
use App\Models\StoreSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorys = [
            [
                'name' => 'Pocket',
                'slug' => 'pocket-' . now()->timestamp,
                'is_active' => true,
            ],
            [
                'name' => 'Bonnel',
                'slug' => 'bonnel-' . now()->timestamp,
                'is_active' => true,
            ],
            [
                'name' => 'Hybird',
                'slug' => 'hybird-' . now()->timestamp,
                'is_active' => true,
            ],
            [
                'name' => 'Mattress',
                'slug' => 'mattress-' . now()->timestamp,
                'is_active' => true,
            ],
        ];

        foreach ($categorys as $category) {
            Category::create($category);
        }


        $brands = [
            [
                'name' => 'King Koil',
                'is_active' => true,
            ],
            [
                'name' => 'Serta',
                'is_active' => true,
            ],
            [
                'name' => 'Comforta',
                'is_active' => true,
            ],
            [
                'name' => 'Florence',
                'is_active' => true,
            ],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }

        $couriers = [
            [
                'name' => 'JNE',
                'type' => TypeCourierEnum::EXTERNAL,
                'shipping_cost' => 100000,
                'is_active' => true,
            ],
            [
                'name' => 'SiCepat',
                'type' => TypeCourierEnum::EXTERNAL,
                'shipping_cost' => 120000,
                'is_active' => true,
            ],
            [
                'name' => 'J&T',
                'type' => TypeCourierEnum::INTERNAL,
                'shipping_cost' => 130000,
                'is_active' => true,
            ],
            [
                'name' => 'Kurir Toko',
                'type' => TypeCourierEnum::INTERNAL,
                'shipping_cost' => 140000,
                'is_active' => true,
            ],
        ];

        foreach ($couriers as $courier) {
            Courier::create($courier);
        }

        $customers = [
            [
                'name' => 'John Doe',
                'phone' => '1234567890',
                'email' => 'johndoe@yahoo.com',
                'address' => '123 Main St',
            ],
            [
                'name' => 'Jane Doe',
                'phone' => '123153242',
                'email' => 'janedoe@yahoo.com',
                'address' => '123 Main St',
            ],
            [
                'name' => 'David Smith',
                'phone' => '64646546',
                'email' => 'david@yahoo.com',
                'address' => '123 Main St',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }

        $store_settings = [
            [
                'company_name' => 'Serba Indah',
                'store_name' => 'Toko 1',
                'phone' => '1234567890',
                'email' => 'toko1@yahoo.com',
                'address' => '123 Main St',
            ],
            [
                'company_name' => 'Serba Indah',
                'store_name' => 'Toko 2',
                'phone' => '123153242',
                'email' => 'toko2@yahoo.com',
                'address' => '123 Main St',
            ],
            [
                'company_name' => 'Serba Indah',
                'store_name' => 'Toko 3',
                'phone' => '64646546',
                'email' => 'toko3@yahoo.com',
                'address' => '123 Main St',
            ],
        ];

        foreach ($store_settings as $store) {
            StoreSetting::create($store);
        }
    }
}
