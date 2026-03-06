<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'trip_schedule_id',
        'booking_code',
        'pax_count',
        'total_price',
        'status',
        'special_requests'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tripSchedule()
    {
        return $this->belongsTo(TripSchedule::class);
    }

    public function passengers()
    {
        return $this->hasMany(BookingPassenger::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
