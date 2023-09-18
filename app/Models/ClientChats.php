<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin Builder
 */
class ClientChats extends Model
{
    protected $guarded = [];

    protected $casts = [
        'chat_id' => 'string',
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chats::class,'chat_id','chat_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class,'id','client_id');
    }

}
