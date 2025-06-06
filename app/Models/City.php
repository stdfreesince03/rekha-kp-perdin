<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'province',
        'island',
        'is_foreign',
        'country',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'is_foreign' => 'boolean',
        'is_active' => 'boolean',
    ];


    public function originTrips()
    {
        return $this->hasMany(BusinessTrip::class, 'origin_city_id');
    }

    public function destinationTrips()
    {
        return $this->hasMany(BusinessTrip::class, 'destination_city_id');
    }


    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDomestic($query)
    {
        return $query->where('is_foreign', false);
    }

    public function scopeForeign($query)
    {
        return $query->where('is_foreign', true);
    }
}
