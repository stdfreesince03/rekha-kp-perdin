<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BusinessTrip extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_number',
        'user_id',
        'purpose',
        'departure_date',
        'return_date',
        'origin_city_id',
        'destination_city_id',
        'duration_days',
        'distance_km',
        'daily_allowance',
        'total_allowance',
        'currency',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'notes',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'return_date' => 'date',
        'distance_km' => 'decimal:2',
        'daily_allowance' => 'decimal:2',
        'total_allowance' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($trip) {
            // Generate trip number
            if (empty($trip->trip_number)) {
                $trip->trip_number = static::generateTripNumber();
            }

            // Auto-calculate duration
            if ($trip->departure_date && $trip->return_date) {
                $trip->duration_days = $trip->calculateDuration();
            }
        });

        static::created(function ($trip) {
            // Calculate distance and allowance after creation
            $trip->calculateDistanceAndAllowanceDirectly();
        });

        static::updating(function ($trip) {
            // Recalculate duration if dates change
            if ($trip->isDirty(['departure_date', 'return_date'])) {
                $trip->duration_days = $trip->calculateDuration();
            }

            // Recalculate distance/allowance if cities or dates change
            if ($trip->isDirty(['origin_city_id', 'destination_city_id', 'departure_date', 'return_date'])) {
                $trip->calculateDistanceAndAllowanceDirectly();
            }
        });
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function originCity()
    {
        return $this->belongsTo(City::class, 'origin_city_id');
    }

    public function destinationCity()
    {
        return $this->belongsTo(City::class, 'destination_city_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function documents()
    {
        return $this->hasMany(TripDocument::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'PENDING');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'APPROVED');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'REJECTED');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('departure_date', [$startDate, $endDate]);
    }

    // Helper methods
    public static function generateTripNumber()
    {
        $year = date('Y');
        $month = date('m');
        $lastTrip = static::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastTrip ? intval(substr($lastTrip->trip_number, -4)) + 1 : 1;

        return sprintf('PERDIN-%s%s-%04d', $year, $month, $sequence);
    }

    public function calculateDuration()
    {
        if (!$this->departure_date || !$this->return_date) {
            return 0;
        }

        return Carbon::parse($this->departure_date)
                ->diffInDays(Carbon::parse($this->return_date)) + 1;
    }

    // Calculate distance and allowance directly (without service provider)
    public function calculateDistanceAndAllowanceDirectly()
    {
        // Skip if cities are not loaded or don't exist
        if (!$this->originCity || !$this->destinationCity) {
            return;
        }

        $distanceCalc = new \App\Services\DistanceCalculator();
        $allowanceCalc = new \App\Services\AllowanceCalculator();

        // Calculate distance
        $distance = $distanceCalc->calculateDistance(
            $this->originCity->latitude,
            $this->originCity->longitude,
            $this->destinationCity->latitude,
            $this->destinationCity->longitude
        );

        // Calculate duration
        $duration = $this->calculateDuration();

        // Calculate allowance
        $allowanceData = $allowanceCalc->calculateTotalAllowance(
            $this->originCity,
            $this->destinationCity,
            $distance,
            $duration
        );

        // Update without triggering events (to prevent infinite loop)
        $this->updateQuietly([
            'distance_km' => $distance,
            'duration_days' => $duration,
            'daily_allowance' => $allowanceData['daily_allowance'],
            'total_allowance' => $allowanceData['total_allowance'],
            'currency' => $allowanceData['currency'],
        ]);
    }

    // Status methods
    public function isPending()
    {
        return $this->status === 'PENDING';
    }

    public function isApproved()
    {
        return $this->status === 'APPROVED';
    }

    public function isRejected()
    {
        return $this->status === 'REJECTED';
    }

    public function approve($approverId, $notes = null)
    {
        $this->update([
            'status' => 'APPROVED',
            'approved_by' => $approverId,
            'approved_at' => now(),
            'notes' => $notes,
        ]);
    }

    public function reject($approverId, $reason)
    {
        $this->update([
            'status' => 'REJECTED',
            'approved_by' => $approverId,
            'approved_at' => now(),
            'rejection_reason' => $reason,
        ]);
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'PENDING' => '<span class="badge bg-warning">Pending</span>',
            'APPROVED' => '<span class="badge bg-success">Approved</span>',
            'REJECTED' => '<span class="badge bg-danger">Rejected</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    public function getFormattedAllowanceAttribute()
    {
        if ($this->currency === 'USD') {
            return '$' . number_format($this->total_allowance, 2);
        }

        return 'Rp ' . number_format($this->total_allowance, 0, ',', '.');
    }

    public function getRouteDisplayAttribute()
    {
        return $this->originCity->name . ' → ' . $this->destinationCity->name;
    }

    // Statistics method
    public function getStatistics()
    {
        return [
            'route' => $this->route_display,
            'distance_km' => $this->distance_km,
            'duration_days' => $this->duration_days,
            'daily_allowance' => $this->daily_allowance,
            'total_allowance' => $this->total_allowance,
            'currency' => $this->currency,
            'cost_per_km' => $this->distance_km > 0 ? round($this->total_allowance / $this->distance_km, 2) : 0,
            'cost_per_day' => $this->daily_allowance,
            'average_distance_per_day' => $this->duration_days > 0 ? round($this->distance_km / $this->duration_days, 2) : 0,
        ];
    }
}
