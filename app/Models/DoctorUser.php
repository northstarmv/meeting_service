<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
/**
 * @mixin Builder
 */
class DoctorUser extends Model
{
    protected $connection = 'accounts';
    protected $table = 'user__doctors';
    protected $primaryKey = 'user_id';

    protected $casts = [
        'online' => 'boolean',
    ];
}
