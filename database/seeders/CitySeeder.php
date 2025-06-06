<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            [
                'name' => 'Jakarta',
                'latitude' => -6.200000,
                'longitude' => 106.816666,
                'province' => 'DKI Jakarta',
                'island' => 'Jawa',
                'is_foreign' => false,
                'country' => 'Indonesia',
                'is_active' => true,
            ],
            [
                'name' => 'Bandung',
                'latitude' => -6.917500,
                'longitude' => 107.619100,
                'province' => 'Jawa Barat',
                'island' => 'Jawa',
                'is_foreign' => false,
                'country' => 'Indonesia',
                'is_active' => true,
            ],
            [
                'name' => 'Surabaya',
                'latitude' => -7.257472,
                'longitude' => 112.752090,
                'province' => 'Jawa Timur',
                'island' => 'Jawa',
                'is_foreign' => false,
                'country' => 'Indonesia',
                'is_active' => true,
            ],
            [
                'name' => 'Yogyakarta',
                'latitude' => -7.797068,
                'longitude' => 110.370529,
                'province' => 'DI Yogyakarta',
                'island' => 'Jawa',
                'is_foreign' => false,
                'country' => 'Indonesia',
                'is_active' => true,
            ],
            [
                'name' => 'Semarang',
                'latitude' => -6.966667,
                'longitude' => 110.416664,
                'province' => 'Jawa Tengah',
                'island' => 'Jawa',
                'is_foreign' => false,
                'country' => 'Indonesia',
                'is_active' => true,
            ],

            [
                'name' => 'Medan',
                'latitude' => 3.595196,
                'longitude' => 98.672226,
                'province' => 'Sumatera Utara',
                'island' => 'Sumatera',
                'is_foreign' => false,
                'country' => 'Indonesia',
                'is_active' => true,
            ],
            [
                'name' => 'Palembang',
                'latitude' => -2.998056,
                'longitude' => 104.756111,
                'province' => 'Sumatera Selatan',
                'island' => 'Sumatera',
                'is_foreign' => false,
                'country' => 'Indonesia',
                'is_active' => true,
            ],
            [
                'name' => 'Pekanbaru',
                'latitude' => 0.533333,
                'longitude' => 101.450000,
                'province' => 'Riau',
                'island' => 'Sumatera',
                'is_foreign' => false,
                'country' => 'Indonesia',
                'is_active' => true,
            ],

            [
                'name' => 'Balikpapan',
                'latitude' => -1.239556,
                'longitude' => 116.853889,
                'province' => 'Kalimantan Timur',
                'island' => 'Kalimantan',
                'is_foreign' => false,
                'country' => 'Indonesia',
                'is_active' => true,
            ],
            [
                'name' => 'Banjarmasin',
                'latitude' => -3.325000,
                'longitude' => 114.583333,
                'province' => 'Kalimantan Selatan',
                'island' => 'Kalimantan',
                'is_foreign' => false,
                'country' => 'Indonesia',
                'is_active' => true,
            ],

            [
                'name' => 'Makassar',
                'latitude' => -5.135000,
                'longitude' => 119.423333,
                'province' => 'Sulawesi Selatan',
                'island' => 'Sulawesi',
                'is_foreign' => false,
                'country' => 'Indonesia',
                'is_active' => true,
            ],
            [
                'name' => 'Manado',
                'latitude' => 1.474830,
                'longitude' => 124.842079,
                'province' => 'Sulawesi Utara',
                'island' => 'Sulawesi',
                'is_foreign' => false,
                'country' => 'Indonesia',
                'is_active' => true,
            ],
            [
                'name' => 'Denpasar',
                'latitude' => -8.650000,
                'longitude' => 115.216667,
                'province' => 'Bali',
                'island' => 'Bali',
                'is_foreign' => false,
                'country' => 'Indonesia',
                'is_active' => true,
            ],

            [
                'name' => 'Jayapura',
                'latitude' => -2.533333,
                'longitude' => 140.716667,
                'province' => 'Papua',
                'island' => 'Papua',
                'is_foreign' => false,
                'country' => 'Indonesia',
                'is_active' => true,
            ],
            [
                'name' => 'Singapore',
                'latitude' => 1.290270,
                'longitude' => 103.851959,
                'province' => '',
                'island' => '',
                'is_foreign' => true,
                'country' => 'Singapore',
                'is_active' => true,
            ],
            [
                'name' => 'Kuala Lumpur',
                'latitude' => 3.139003,
                'longitude' => 101.686855,
                'province' => '',
                'island' => '',
                'is_foreign' => true,
                'country' => 'Malaysia',
                'is_active' => true,
            ],
        ];

        foreach ($cities as $city) {
            City::create($city);
        }
    }
}
