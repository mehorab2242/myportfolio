<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class PortfolioMedia extends Model
{
    protected $table = 'portfolio_media';

    protected $fillable = [
        'portfolio_item_id',
        'file_path',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'order' => 'integer',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(PortfolioItem::class, 'portfolio_item_id');
    }

    public function url(): ?string
    {
        return $this->file_path
            ? Storage::disk('public')->url($this->file_path)
            : null;
    }
}
