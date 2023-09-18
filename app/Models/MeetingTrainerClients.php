<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin Builder
 */
class MeetingTrainerClients extends Model
{
    protected $guarded = [];
    protected $connection = 'mysql';

    protected $casts = [
        'clients' => 'json',
        'finished' => 'boolean',
    ];

    public function owner(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'trainer_id');
    }
}
