<?php
namespace App\Services;

use App\Models\BusinessTrip;
use App\Models\City;
use Carbon\Carbon;

class TripCalculationService
{
    protected $distanceCalculator;
    protected $allowanceCalculator;

    public function __construct(
        DistanceCalculator $distanceCalculator,
        AllowanceCalculator $allowanceCalculator
    ) {
        $this->distanceCalculator = $distanceCalculator;
        $this->allowanceCalculator = $allowanceCalculator;
    }

    public function calculateTripDetails(array $tripData)
    {
        $originCity = City::find($tripData['origin_city_id']);
        $destinationCity = City::find($tripData['destination_city_id']);

        if (!$originCity || !$destinationCity) {
            throw new \Exception('Invalid city data');
        }

        // Validate trip
        $validation = $this->allowanceCalculator->validateTrip($originCity, $destinationCity);
        if (!$validation['valid']) {
            throw new \Exception('Trip validation failed: ' . implode(', ', $validation['errors']));
        }

        // Calculate distance
        $distance = $this->distanceCalculator->calculateDistance(
            $originCity->latitude,
            $originCity->longitude,
            $destinationCity->latitude,
            $destinationCity->longitude
        );

        // Calculate duration
        $departureDate = Carbon::parse($tripData['departure_date']);
        $returnDate = Carbon::parse($tripData['return_date']);
        $durationDays = $departureDate->diffInDays($returnDate) + 1;

        // Calculate allowance
        $allowanceDetails = $this->allowanceCalculator->calculateTotalAllowance(
            $originCity,
            $destinationCity,
            $distance,
            $durationDays
        );

        return [
            'origin_city' => $originCity,
            'destination_city' => $destinationCity,
            'distance_km' => $distance,
            'duration_days' => $durationDays,
            'daily_allowance' => $allowanceDetails['daily_allowance'],
            'currency' => $allowanceDetails['currency'],
            'total_allowance' => $allowanceDetails['total_allowance'],
            'allowance_category' => $allowanceDetails['category'],
            'allowance_description' => $allowanceDetails['description'],
            'rate_info' => $allowanceDetails['rate_info'],
            'breakdown' => $allowanceDetails['breakdown'],
            'distance_info' => $this->distanceCalculator->getDistanceInUnits($distance)
        ];
    }

    public function updateTripCalculations(BusinessTrip $trip)
    {
        $calculations = $this->calculateTripDetails([
            'origin_city_id' => $trip->origin_city_id,
            'destination_city_id' => $trip->destination_city_id,
            'departure_date' => $trip->departure_date,
            'return_date' => $trip->return_date,
        ]);

        $trip->update([
            'distance_km' => $calculations['distance_km'],
            'duration_days' => $calculations['duration_days'],
            'daily_allowance' => $calculations['daily_allowance'],
            'currency' => $calculations['currency'],
            'total_allowance' => $calculations['total_allowance'],
        ]);

        return $trip;
    }

    public function calculateAllowancePreview($originCityId, $destinationCityId, $departureDate, $returnDate)
    {
        try {
            $calculations = $this->calculateTripDetails([
                'origin_city_id' => $originCityId,
                'destination_city_id' => $destinationCityId,
                'departure_date' => $departureDate,
                'return_date' => $returnDate,
            ]);

            return [
                'success' => true,
                'data' => [
                    'distance' => $calculations['distance_km'],
                    'duration' => $calculations['duration_days'],
                    'daily_allowance' => $calculations['daily_allowance'],
                    'total_allowance' => $calculations['total_allowance'],
                    'currency' => $calculations['currency'],
                    'category' => $calculations['allowance_category'],
                    'breakdown' => $calculations['breakdown'],
                    'formatted_distance' => number_format($calculations['distance_km'], 2) . ' km',
                    'formatted_duration' => $calculations['duration_days'] . ' hari',
                    'formatted_allowance' => $calculations['currency'] === 'USD'
                        ? '$' . number_format($calculations['total_allowance'], 2)
                        : 'Rp ' . number_format($calculations['total_allowance'], 0, ',', '.')
                ]
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }


    public function getTripStatistics(BusinessTrip $trip)
    {
        return [
            'route' => $trip->originCity->name . ' → ' . $trip->destinationCity->name,
            'distance_km' => $trip->distance_km,
            'duration_days' => $trip->duration_days,
            'daily_allowance' => $trip->daily_allowance,
            'total_allowance' => $trip->total_allowance,
            'currency' => $trip->currency,
            'cost_per_km' => $trip->distance_km > 0 ? round($trip->total_allowance / $trip->distance_km, 2) : 0,
            'cost_per_day' => $trip->daily_allowance,
            'average_distance_per_day' => $trip->duration_days > 0 ? round($trip->distance_km / $trip->duration_days, 2) : 0,
        ];
    }
}
