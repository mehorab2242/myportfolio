<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Experience extends Model
{
    // Explicit table — Laravel may treat "experience" as uncountable.
    protected $table = 'experiences';

    public const TYPE_FULL_TIME = 'full_time';

    public const TYPE_PART_TIME = 'part_time';

    public const TYPE_FREELANCE = 'freelance';

    public const TYPE_INTERNSHIP = 'internship';

    public const TYPE_CONTRACT = 'contract';

    public const TYPE_OTHER = 'other';

    public const EMPLOYMENT_TYPES = [
        self::TYPE_FULL_TIME,
        self::TYPE_PART_TIME,
        self::TYPE_FREELANCE,
        self::TYPE_INTERNSHIP,
        self::TYPE_CONTRACT,
        self::TYPE_OTHER,
    ];

    protected $fillable = [
        'user_id',
        'title',
        'organization',
        'employment_type',
        'location',
        'start_date',
        'end_date',
        'is_current',
        'description',
        'is_active',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_current' => 'boolean',
            'is_active' => 'boolean',
            'order' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
