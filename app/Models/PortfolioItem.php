<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PortfolioItem extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'short_description',
        'category_id',
        'client_name',
        'project_url',
        'start_date',
        'end_date',
        'is_featured',
        'is_active',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'order' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PortfolioCategory::class, 'category_id');
    }

    public function media(): HasMany
    {
        return $this->hasMany(PortfolioMedia::class)->orderBy('order');
    }
}
