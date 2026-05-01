<?php

namespace App\Imports;

use App\Enums\SizeProductEnum;
use App\Enums\TypeProductEnum;
use App\Models\Brand;
use App\Models\Category;
use App\Models\InventoryStock;
use App\Models\Product;
use App\Models\StoreSetting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    public int   $importedCount = 0;
    public array $errors        = [];

    public function headingRow(): int
    {
        return 2;
    }

    public function collection(Collection $rows): void
    {
        $validTypes = collect(TypeProductEnum::cases())->pluck('value')->toArray();
        $validSizes = collect(SizeProductEnum::cases())->pluck('value')->toArray();

        $storeMap = StoreSetting::all()->keyBy(
            fn(StoreSetting $s) => strtolower(trim($s->store_name))
        );

        $brandMap    = Brand::all()->keyBy(fn($b) => strtolower(trim($b->name)));
        $categoryMap = Category::all()->keyBy(fn($c) => strtolower(trim($c->name)));

        $loggedInUserStoreId = Auth::user()?->store_setting_id;

        DB::transaction(function () use (
            $rows,
            $validTypes,
            $validSizes,
            $storeMap,
            $brandMap,
            $categoryMap,
            $loggedInUserStoreId
        ) {
            foreach ($rows as $index => $row) {
                $rowNum   = $index + 3;
                $rowArray = $row->toArray();

                $storeName    = strtolower(trim($rowArray['store_name']    ?? ''));
                $brandName    = strtolower(trim($rowArray['brand_name']    ?? ''));
                $categoryName = strtolower(trim($rowArray['category_name'] ?? ''));
                $name         = trim($rowArray['name']          ?? '');
                $type         = strtolower(trim($rowArray['type']          ?? ''));
                $size         = strtolower(trim($rowArray['size']          ?? ''));
                $sellingPrice = $rowArray['selling_price'] ?? null;
                $sku          = $rowArray['sku']       ?? null;
                $weight       = $rowArray['weight']    ?? null;
                $color        = $rowArray['color']     ?? null;
                $isActive     = $rowArray['is_active'] ?? 1;

                $resolvedStore  = $storeMap[$storeName] ?? null;
                $storeSettingId = null;

                if ($resolvedStore) {
                    if ($loggedInUserStoreId && $resolvedStore->id !== $loggedInUserStoreId) {
                        $this->errors[] = [
                            'row'    => $rowNum,
                            'name'   => $name ?: '(empty)',
                            'errors' => ["Store '{$rowArray['store_name']}' tidak sesuai dengan toko Anda."],
                        ];
                        continue;
                    }
                    $storeSettingId = $resolvedStore->id;
                } else {
                    $storeSettingId = $loggedInUserStoreId;
                }

                $stockEntries = [];

                foreach ($rowArray as $key => $value) {
                    if (!str_starts_with((string) $key, 'stock_')) {
                        continue;
                    }

                    $storeSlug = substr($key, strlen('stock_'));

                    $store = $storeMap[strtolower($storeSlug)]
                        ?? $storeMap[strtolower(str_replace('_', ' ', $storeSlug))]
                        ?? null;

                    if (!$store) {
                        continue;
                    }

                    if ($loggedInUserStoreId && $store->id !== $loggedInUserStoreId) {
                        continue;
                    }

                    $qty = is_numeric($value) ? max(0, (int) $value) : 0;
                    $stockEntries[$store->id] = $qty;
                }

                if ($brandName === '' && $name === '') {
                    continue;
                }

                $rowErrors = [];

                if ($brandName === '') {
                    $rowErrors[] = 'brand_name is required';
                }
                if ($categoryName === '') {
                    $rowErrors[] = 'category_name is required';
                }
                if ($name === '') {
                    $rowErrors[] = 'name is required';
                }
                if (!in_array($type, $validTypes, true)) {
                    $rowErrors[] = "type '{$type}' is invalid. Must be one of: " . implode(', ', $validTypes);
                }
                if (!in_array($size, $validSizes, true)) {
                    $rowErrors[] = "size '{$size}' is invalid. Must be one of: " . implode(', ', $validSizes);
                }
                if (!is_numeric($sellingPrice) || (float) $sellingPrice < 0) {
                    $rowErrors[] = 'selling_price must be a positive number';
                }

                if (!empty($rowErrors)) {
                    $this->errors[] = [
                        'row'    => $rowNum,
                        'name'   => $name ?: '(empty)',
                        'errors' => $rowErrors,
                    ];
                    continue;
                }

                $brand = $brandMap[$brandName] ?? null;
                if (!$brand) {
                    $this->errors[] = [
                        'row'    => $rowNum,
                        'name'   => $name,
                        'errors' => ["Brand '{$rowArray['brand_name']}' not found in the system."],
                    ];
                    continue;
                }

                $category = $categoryMap[$categoryName] ?? null;
                if (!$category) {
                    $this->errors[] = [
                        'row'    => $rowNum,
                        'name'   => $name,
                        'errors' => ["Category '{$rowArray['category_name']}' not found in the system."],
                    ];
                    continue;
                }

                $product = Product::create([
                    'store_setting_id' => $storeSettingId,
                    'brand_id'         => $brand->id,
                    'category_id'      => $category->id,
                    'name'             => $name,
                    'type'             => $type,
                    'size'             => $size,
                    'selling_price'    => (float) $sellingPrice,
                    'sku'              => $sku ?: null,
                    'weight'           => is_numeric($weight) ? (float) $weight : null,
                    'color'            => $color ?: null,
                    'is_active'        => (bool) (int) $isActive,
                ]);

                foreach ($stockEntries as $storeId => $qty) {
                    InventoryStock::updateOrCreate(
                        [
                            'product_id'       => $product->id,
                            'store_setting_id' => $storeId,
                        ],
                        ['quantity' => $qty]
                    );
                }

                $this->importedCount++;
            }
        });
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
