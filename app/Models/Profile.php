<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Profile extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'title',
        'bio',
        'about',
        'avatar',
        'cover_image',
        'location',
        'email_public',
        'phone',
        'phone_public',
        'website',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_public' => 'boolean',
            'phone_public' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function avatarUrl(): ?string
    {
        return $this->avatar ? Storage::disk('public')->url($this->avatar) : null;
    }

    public function coverImageUrl(): ?string
    {
        return $this->cover_image ? Storage::disk('public')->url($this->cover_image) : null;
    }
}
