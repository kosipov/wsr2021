<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Flight extends Model
{
    use HasFactory;

    public function airportFrom(): BelongsTo
    {
        return $this->belongsTo(Airport::class, 'from_id', 'id');
    }

    public function airportTo(): BelongsTo
    {
        return $this->belongsTo(Airport::class, 'to_id', 'id');
    }
}
