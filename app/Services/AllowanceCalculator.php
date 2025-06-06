<?php
namespace App\Services;

use App\Models\City;

class AllowanceCalculator
{
    const RATE_SAME_PROVINCE = 200000;
    const RATE_DIFFERENT_PROVINCE_SAME_ISLAND = 250000;
    const RATE_DIFFERENT_ISLAND = 300000;
    const RATE_FOREIGN_USD = 50; // USD

    const MIN_DISTANCE_KM = 60;

    public function calculateDailyAllowance(City $originCity, City $destinationCity, float $distance)
    {
        // Special case: Foreign country
        if ($destinationCity->is_foreign) {
            return [
                'amount' => self::RATE_FOREIGN_USD,
                'currency' => 'USD',
                'category' => 'Luar Negeri',
                'description' => 'Perjalanan dinas ke luar negeri',
                'rate_info' => 'USD ' . self::RATE_FOREIGN_USD . ' per hari'
            ];
        }

        // Distance less than minimum threshold
        if ($distance <= self::MIN_DISTANCE_KM) {
            return [
                'amount' => 0,
                'currency' => 'IDR',
                'category' => 'Tidak Mendapat Uang Saku',
                'description' => 'Jarak kurang dari ' . self::MIN_DISTANCE_KM . ' km',
                'rate_info' => 'Jarak ' . $distance . ' km (minimum ' . self::MIN_DISTANCE_KM . ' km)'
            ];
        }

        // Same province
        if ($originCity->province === $destinationCity->province) {
            return [
                'amount' => self::RATE_SAME_PROVINCE,
                'currency' => 'IDR',
                'category' => 'Dalam Provinsi',
                'description' => 'Perjalanan dalam satu provinsi (' . $originCity->province . ')',
                'rate_info' => 'Rp ' . number_format(self::RATE_SAME_PROVINCE, 0, ',', '.') . ' per hari'
            ];
        }

        // Same island, different province
        if ($originCity->island === $destinationCity->island) {
            return [
                'amount' => self::RATE_DIFFERENT_PROVINCE_SAME_ISLAND,
                'currency' => 'IDR',
                'category' => 'Luar Provinsi - Dalam Pulau',
                'description' => "Perjalanan dari {$originCity->province} ke {$destinationCity->province} dalam pulau {$originCity->island}",
                'rate_info' => 'Rp ' . number_format(self::RATE_DIFFERENT_PROVINCE_SAME_ISLAND, 0, ',', '.') . ' per hari'
            ];
        }

        // Different island
        return [
            'amount' => self::RATE_DIFFERENT_ISLAND,
            'currency' => 'IDR',
            'category' => 'Luar Pulau',
            'description' => "Perjalanan dari pulau {$originCity->island} ke pulau {$destinationCity->island}",
            'rate_info' => 'Rp ' . number_format(self::RATE_DIFFERENT_ISLAND, 0, ',', '.') . ' per hari'
        ];
    }


    public function calculateTotalAllowance(City $originCity, City $destinationCity, float $distance, int $durationDays)
    {
        $dailyAllowance = $this->calculateDailyAllowance($originCity, $destinationCity, $distance);

        return [
            'daily_allowance' => $dailyAllowance['amount'],
            'currency' => $dailyAllowance['currency'],
            'duration_days' => $durationDays,
            'total_allowance' => $dailyAllowance['amount'] * $durationDays,
            'category' => $dailyAllowance['category'],
            'description' => $dailyAllowance['description'],
            'rate_info' => $dailyAllowance['rate_info'],
            'breakdown' => $this->getFormattedBreakdown($dailyAllowance['amount'], $dailyAllowance['currency'], $durationDays)
        ];
    }


    public function getFormattedBreakdown(float $dailyAmount, string $currency, int $days)
    {
        if ($currency === 'USD') {
            return sprintf('$%.2f x %d hari = $%.2f', $dailyAmount, $days, $dailyAmount * $days);
        }

        if ($dailyAmount == 0) {
            return 'Tidak mendapat uang saku perjalanan dinas';
        }

        return sprintf('Rp %s x %d hari = Rp %s',
            number_format($dailyAmount, 0, ',', '.'),
            $days,
            number_format($dailyAmount * $days, 0, ',', '.')
        );
    }


    public function getAllowanceRates()
    {
        return [
            'domestic' => [
                'same_province' => [
                    'amount' => self::RATE_SAME_PROVINCE,
                    'currency' => 'IDR',
                    'description' => 'Perjalanan dalam satu provinsi',
                    'condition' => 'Jarak > 60km, provinsi sama'
                ],
                'different_province_same_island' => [
                    'amount' => self::RATE_DIFFERENT_PROVINCE_SAME_ISLAND,
                    'currency' => 'IDR',
                    'description' => 'Perjalanan luar provinsi dalam satu pulau',
                    'condition' => 'Jarak > 60km, provinsi beda, pulau sama'
                ],
                'different_island' => [
                    'amount' => self::RATE_DIFFERENT_ISLAND,
                    'currency' => 'IDR',
                    'description' => 'Perjalanan antar pulau',
                    'condition' => 'Jarak > 60km, pulau beda'
                ],
                'no_allowance' => [
                    'amount' => 0,
                    'currency' => 'IDR',
                    'description' => 'Tidak mendapat uang saku',
                    'condition' => 'Jarak ≤ 60km'
                ]
            ],
            'foreign' => [
                'amount' => self::RATE_FOREIGN_USD,
                'currency' => 'USD',
                'description' => 'Perjalanan dinas ke luar negeri',
                'condition' => 'Destinasi luar negeri'
            ],
            'minimum_distance' => self::MIN_DISTANCE_KM
        ];
    }


    public function validateTrip(City $originCity, City $destinationCity)
    {
        $errors = [];

        if ($originCity->id === $destinationCity->id) {
            $errors[] = 'Kota asal dan tujuan tidak boleh sama';
        }

        if (!$originCity->is_active) {
            $errors[] = 'Kota asal tidak aktif';
        }

        if (!$destinationCity->is_active) {
            $errors[] = 'Kota tujuan tidak aktif';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
}
