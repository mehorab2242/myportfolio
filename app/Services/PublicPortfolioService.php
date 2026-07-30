<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PublicPortfolioService
{
    /**
     * Resolve a public portfolio by username (active records only).
     * Does NOT use auth() — multi-tenant isolation via username → user_id.
     */
    public function resolveByUsername(string $username): User
    {
        $username = strtolower(trim($username));

        if (in_array($username, config('portfolio.reserved_usernames', []), true)) {
            throw (new ModelNotFoundException)->setModel(User::class, [$username]);
        }

        $user = User::query()
            ->where('username', $username)
            ->where('status', true)
            ->first();

        if (! $user) {
            throw (new ModelNotFoundException)->setModel(User::class, [$username]);
        }

        $user->load([
            'profile',
            'socialLinks' => fn ($q) => $q->orderBy('platform'),
            'professionalMeta' => fn ($q) => $q->orderBy('key'),
            'skillCategories' => fn ($q) => $q
                ->where('is_active', true)
                ->orderBy('order')
                ->with(['skills' => fn ($sq) => $sq->where('is_active', true)->orderBy('order')]),
            'portfolioCategories' => fn ($q) => $q
                ->where('is_active', true)
                ->orderBy('order'),
            'portfolioItems' => fn ($q) => $q
                ->where('is_active', true)
                ->orderBy('order')
                ->with(['category', 'media' => fn ($mq) => $mq->orderBy('order')]),
            'experiences' => fn ($q) => $q
                ->where('is_active', true)
                ->orderBy('order'),
            'educations' => fn ($q) => $q
                ->where('is_active', true)
                ->orderBy('order'),
        ]);

        return $user;
    }
}
