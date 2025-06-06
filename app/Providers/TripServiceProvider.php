<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\DistanceCalculator;
use App\Services\AllowanceCalculator;
use App\Services\TripCalculationService;

class TripServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->singleton(DistanceCalculator::class, function ($app) {
            return new DistanceCalculator();
        });

        $this->app->singleton(AllowanceCalculator::class, function ($app) {
            return new AllowanceCalculator();
        });

        $this->app->singleton(TripCalculationService::class, function ($app) {
            return new TripCalculationService(
                $app->make(DistanceCalculator::class),
                $app->make(AllowanceCalculator::class)
            );
        });
    }


    public function boot(): void
    {

    }
}
