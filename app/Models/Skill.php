<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Skill extends Model
{
    public const LEVEL_PERCENTAGE = 'percentage';

    public const LEVEL_TEXT = 'text';

    public const LEVEL_STARS = 'stars';

    public const LEVEL_TYPES = [
        self::LEVEL_PERCENTAGE,
        self::LEVEL_TEXT,
        self::LEVEL_STARS,
    ];

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'level',
        'level_type',
        'is_featured',
        'is_active',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'order' => 'integer',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(SkillCategory::class, 'category_id');
    }
}
