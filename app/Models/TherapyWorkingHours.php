<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TherapyWorkingHours extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(user_therapy::class, 'therapy_id','id');
    }
}
