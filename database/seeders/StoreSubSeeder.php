<?php

namespace Database\Seeders;

use App\Enums\StoreSubTypeEnum;
use App\Models\StoreSetting;
use App\Models\StoreSub;
use Illuminate\Database\Seeder;

class StoreSubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stores = StoreSetting::all();

        foreach ($stores as $store) {
            StoreSub::firstOrCreate(
                [
                    'store_id' => $store->id,
                    'type'     => StoreSubTypeEnum::FLOOR,
                    'name'     => 'Lantai 1',
                ],
                [
                    'parent_id'  => null,
                    'created_by' => null,
                ]
            );
        }
    }
}
