<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin Builder
 */
class Chats extends Model
{
    protected $primaryKey = 'chat_id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
    protected $connection = 'mysql';

    public function clients(): HasMany
    {
        return $this->hasMany(ClientChats::class,'chat_id','chat_id');
    }

    public function owner(): HasOne
    {
        return $this->hasOne(User::class,'id','owner_id');
    }
}
