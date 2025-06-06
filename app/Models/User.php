<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'employee_id',
        'department',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];


    public function isPegawai()
    {
        return $this->role === 'PEGAWAI';
    }

    public function isSDM()
    {
        return $this->role === 'SDM';
    }


    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePegawai($query)
    {
        return $query->where('role', 'PEGAWAI');
    }

    public function scopeSDM($query)
    {
        return $query->where('role', 'SDM');
    }

    public function businessTrips()
    {
        return $this->hasMany(BusinessTrip::class);
    }

    public function approvedTrips()
    {
        return $this->hasMany(BusinessTrip::class, 'approved_by');
    }
}
