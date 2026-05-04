<?php

namespace Database\Seeders;

use App\Models\ProductType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Springbed'],
            ['name' => 'Divan'],
            ['name' => 'Headboard'],
            ['name' => 'Bundle'],
        ];

        foreach ($types as $type) {
            ProductType::firstOrCreate(['name' => $type['name']], $type);
        }
    }
}
