<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin Builder
 */
class DoctorMeetings extends Model
{
    protected $guarded = [];
    protected $connection = 'mysql';
    protected $casts = [
        'approved' => 'boolean',
        'has_rejected' => 'boolean',
        'finished' => 'boolean',
    ];

    public function doctor(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'doctor_id');
    }

    public function client(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'client_id');
    }

    public function trainer(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'trainer_id');
    }

}
