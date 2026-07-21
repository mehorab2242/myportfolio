<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfessionalMeta extends Model
{
    /**
     * @var string
     */
    protected $table = 'professional_meta';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'key',
        'value',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
