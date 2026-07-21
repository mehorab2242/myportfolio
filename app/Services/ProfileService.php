<?php

namespace App\Services;

use App\Models\ProfessionalMeta;
use App\Models\Profile;
use App\Models\SocialLink;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    /**
     * Load the authenticated user's full profile payload relations.
     */
    public function loadBundle(User $user): User
    {
        return $user->load([
            'profile',
            'socialLinks',
            'professionalMeta',
        ]);
    }

    /**
     * Create or update the user's profile.
     *
     * @param  array<string, mixed>  $data
     */
    public function upsertProfile(User $user, array $data): Profile
    {
        return Profile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'name' => $data['name'],
                'title' => $data['title'],
                'bio' => $data['bio'] ?? null,
                'about' => $data['about'] ?? null,
                'location' => $data['location'] ?? null,
                'email_public' => (bool) ($data['email_public'] ?? false),
                'phone' => $data['phone'] ?? null,
                'phone_public' => (bool) ($data['phone_public'] ?? false),
                'website' => $data['website'] ?? null,
            ]
        );
    }

    /**
     * Ensure a profile row exists before attaching media.
     */
    public function ensureProfile(User $user): Profile
    {
        return Profile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'name' => $user->name,
                'title' => 'Portfolio',
            ]
        );
    }

    public function storeAvatar(User $user, UploadedFile $file): Profile
    {
        $profile = $this->ensureProfile($user);

        if ($profile->avatar) {
            Storage::disk('public')->delete($profile->avatar);
        }

        $path = $this->putUploadedFile($file, "profiles/{$user->id}/avatar");
        $profile->update(['avatar' => $path]);

        return $profile->fresh();
    }

    public function storeCover(User $user, UploadedFile $file): Profile
    {
        $profile = $this->ensureProfile($user);

        if ($profile->cover_image) {
            Storage::disk('public')->delete($profile->cover_image);
        }

        $path = $this->putUploadedFile($file, "profiles/{$user->id}/cover");
        $profile->update(['cover_image' => $path]);

        return $profile->fresh();
    }

    /**
     * Store an uploaded file using its temp pathname directly.
     * Avoids UploadedFile::store(), which relies on getRealPath() —
     * that returns false for uploads in C:\Windows\Temp under php-cgi.
     */
    private function putUploadedFile(UploadedFile $file, string $directory): string
    {
        $path = trim($directory, '/').'/'.$file->hashName();

        $stream = fopen($file->getPathname(), 'r');
        Storage::disk('public')->put($path, $stream);

        if (is_resource($stream)) {
            fclose($stream);
        }

        return $path;
    }

    /**
     * Upsert social links for the user (create or update by id owned by user).
     *
     * @param  array<int, array{id?: int|null, platform: string, url: string}>  $links
     * @return \Illuminate\Support\Collection<int, SocialLink>
     */
    public function syncSocialLinks(User $user, array $links)
    {
        return DB::transaction(function () use ($user, $links) {
            $saved = collect();

            foreach ($links as $linkData) {
                if (! empty($linkData['id'])) {
                    $link = SocialLink::query()
                        ->where('user_id', $user->id)
                        ->where('id', $linkData['id'])
                        ->firstOrFail();

                    $link->update([
                        'platform' => $linkData['platform'],
                        'url' => $linkData['url'],
                    ]);

                    $saved->push($link->fresh());

                    continue;
                }

                $saved->push(SocialLink::create([
                    'user_id' => $user->id,
                    'platform' => $linkData['platform'],
                    'url' => $linkData['url'],
                ]));
            }

            return $saved;
        });
    }

    public function deleteSocialLink(User $user, int $id): void
    {
        SocialLink::query()
            ->where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail()
            ->delete();
    }

    /**
     * Upsert professional meta key/value pairs for the user.
     *
     * @param  array<int, array{key: string, value?: string|null}>  $meta
     * @return \Illuminate\Support\Collection<int, ProfessionalMeta>
     */
    public function syncProfessionalMeta(User $user, array $meta)
    {
        return DB::transaction(function () use ($user, $meta) {
            $saved = collect();

            foreach ($meta as $item) {
                $row = ProfessionalMeta::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'key' => $item['key'],
                    ],
                    [
                        'value' => $item['value'] ?? null,
                    ]
                );

                $saved->push($row);
            }

            return $saved;
        });
    }
}
