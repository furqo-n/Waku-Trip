<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrivateTripRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'name',
        'destinations',
        'interests',
        'start_date_preference',
        'duration_days',
        'budget_min',
        'budget_max',
        'pax_count',
        'notes',
        'status'
    ];

    protected $casts = [
        'destinations' => 'array',
        'interests' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
