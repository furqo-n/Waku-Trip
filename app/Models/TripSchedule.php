<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'start_date',
        'end_date',
        'price',
        'quota',
        'available_seats',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function passengers()
    {
        return $this->hasManyThrough(BookingPassenger::class, Booking::class);
    }

    public function getFullTitleAttribute()
    {
        return $this->package->title . ' (' . $this->start_date->format('M d, Y') . ')';
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
