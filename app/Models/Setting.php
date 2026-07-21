<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public const DEFAULT_ADMIN_PRIMARY = '#0d9488';

    public const DEFAULT_ADMIN_SECONDARY = '#14b8a6';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'admin_primary',
        'admin_secondary',
    ];

    /**
     * Get the singleton settings row (create with defaults if missing).
     */
    public static function current(): self
    {
        $settings = static::query()->first();

        if ($settings) {
            return $settings;
        }

        return static::query()->create([
            'admin_primary' => self::DEFAULT_ADMIN_PRIMARY,
            'admin_secondary' => self::DEFAULT_ADMIN_SECONDARY,
        ]);
    }

    /**
     * Theme payload for API / frontend applyTheme.
     *
     * @return array{admin_primary: string, admin_secondary: string}
     */
    public function adminThemePayload(): array
    {
        return [
            'admin_primary' => $this->normalizedPrimary(),
            'admin_secondary' => $this->normalizedSecondary(),
        ];
    }

    public function normalizedPrimary(): string
    {
        return $this->normalizeHex($this->admin_primary) ?? self::DEFAULT_ADMIN_PRIMARY;
    }

    public function normalizedSecondary(): string
    {
        return $this->normalizeHex($this->admin_secondary) ?? self::DEFAULT_ADMIN_SECONDARY;
    }

    private function normalizeHex(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $value = strtolower(trim($value));

        if (! str_starts_with($value, '#')) {
            $value = '#'.$value;
        }

        if (! preg_match('/^#[0-9a-f]{6}$/', $value)) {
            return null;
        }

        return $value;
    }
}
