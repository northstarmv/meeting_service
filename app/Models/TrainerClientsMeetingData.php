<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin Builder
 */
class TrainerClientsMeetingData extends Model
{
    protected $guarded = [];
    protected $connection = 'mysql';

    public function meeting(): HasOne
    {
        return $this->hasOne(MeetingTrainerClients::class,'id','meeting_id');
    }

}
