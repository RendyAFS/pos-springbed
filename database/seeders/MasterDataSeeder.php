<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
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
                'name' => 'Springbed',
                'slug' => 'springbed-' . now()->timestamp,
                'is_active' => true,
            ],
            [
                'name' => 'Divan',
                'slug' => 'divan-' . now()->timestamp,
                'is_active' => true,
            ],
            [
                'name' => 'Headboard',
                'slug' => 'headboard-' . now()->timestamp,
                'is_active' => true,
            ],
            [
                'name' => 'Bundle',
                'slug' => 'bundle-' . now()->timestamp,
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
                'store_name' => 'Toko 1',
                'phone' => '1234567890',
                'email' => 'toko1@yahoo.com',
                'address' => '123 Main St',
            ],
            [
                'store_name' => 'Toko 2',
                'phone' => '123153242',
                'email' => 'toko2@yahoo.com',
                'address' => '123 Main St',
            ],
            [
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
