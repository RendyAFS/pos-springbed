<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WilayahHelper
{
    public static function getProvinces(): array
    {
        return Cache::remember('wilayah.provinces', now()->addDays(30), function () {
            return self::fetch('https://wilayah.id/api/provinces.json');
        });
    }

    public static function getRegencies(?string $provinceCode): array
    {
        if (blank($provinceCode)) {
            return [];
        }

        return Cache::remember("wilayah.regencies.{$provinceCode}", now()->addDays(30), function () use ($provinceCode) {
            return self::fetch("https://wilayah.id/api/regencies/{$provinceCode}.json");
        });
    }

    public static function getAllRegencies(): array
    {
        return Cache::remember('wilayah.regencies.all', now()->addDays(30), function () {
            $provinces = self::getProvinces();
            $result = [];

            foreach ($provinces as $provinceCode => $provinceName) {
                foreach (self::getRegencies($provinceCode) as $regencyCode => $regencyName) {
                    $result[$regencyCode] = "{$regencyName}, {$provinceName}";
                }
            }

            return $result;
        });
    }

    public static function getDistricts(?string $regencyCode): array
    {
        if (blank($regencyCode)) {
            return [];
        }

        return Cache::remember("wilayah.districts.{$regencyCode}", now()->addDays(30), function () use ($regencyCode) {
            return self::fetch("https://wilayah.id/api/districts/{$regencyCode}.json");
        });
    }

    public static function provinceCodeFromRegencyCode(?string $regencyCode): ?string
    {
        if (blank($regencyCode) || ! str_contains($regencyCode, '.')) {
            return null;
        }

        return explode('.', $regencyCode)[0];
    }

    protected static function fetch(string $url): array
    {
        $response = Http::timeout(10)->get($url);

        if (! $response->successful()) {
            return [];
        }

        return collect($response->json('data', []))
            ->mapWithKeys(fn(array $item) => [$item['code'] => $item['name']])
            ->toArray();
    }
}
