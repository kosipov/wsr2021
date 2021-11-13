<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory;

    public function flightFrom(): BelongsTo
    {
         return $this->belongsTo(Flight::class, 'flight_from', 'id');
    }

    public function flightBack(): ?BelongsTo
    {
        return $this->belongsTo(Flight::class, 'flight_back', 'id');
    }

    public function passengers(): HasMany
    {
        return $this->hasMany(Passenger::class, 'booking_id', 'id');
    }
}
