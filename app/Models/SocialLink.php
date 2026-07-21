<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialLink extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'platform',
        'url',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
