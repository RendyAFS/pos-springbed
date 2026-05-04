<?php

namespace Database\Seeders;

use App\Models\ProductSize;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sizes = [
            ['name' => 'Single'],
            ['name' => 'Queen'],
            ['name' => 'King'],
            ['name' => 'Custom'],
        ];

        foreach ($sizes as $size) {
            ProductSize::firstOrCreate(['name' => $size['name']], $size);
        }
    }
}
