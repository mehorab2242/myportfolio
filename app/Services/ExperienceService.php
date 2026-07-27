<?php

namespace App\Services;

use App\Models\Experience;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ExperienceService
{
    /**
     * @return Collection<int, Experience>
     */
    public function list(User $user): Collection
    {
        return $user->experiences()->orderBy('order')->get();
    }

    public function create(User $user, array $data): Experience
    {
        $order = $data['order'] ?? ((int) $user->experiences()->max('order')) + 1;
        $isCurrent = (bool) ($data['is_current'] ?? false);

        return $user->experiences()->create([
            'title' => $data['title'],
            'organization' => $data['organization'],
            'employment_type' => $data['employment_type'] ?? Experience::TYPE_FULL_TIME,
            'location' => $data['location'] ?? null,
            'start_date' => $data['start_date'] ?? null,
            'end_date' => $isCurrent ? null : ($data['end_date'] ?? null),
            'is_current' => $isCurrent,
            'description' => $data['description'] ?? null,
            'is_active' => $data['is_active'] ?? true,
            'order' => $order,
        ]);
    }

    public function update(User $user, int $id, array $data): Experience
    {
        $experience = $this->findOwned($user, $id);

        if (array_key_exists('is_current', $data) && $data['is_current']) {
            $data['end_date'] = null;
        }

        $experience->update($data);

        return $experience->fresh();
    }

    public function delete(User $user, int $id): void
    {
        $this->findOwned($user, $id)->delete();
    }

    public function toggle(User $user, int $id): Experience
    {
        $experience = $this->findOwned($user, $id);
        $experience->update(['is_active' => ! $experience->is_active]);

        return $experience->fresh();
    }

    /**
     * @param  array<int, int>  $ids
     * @return Collection<int, Experience>
     */
    public function reorder(User $user, array $ids): Collection
    {
        DB::transaction(function () use ($user, $ids) {
            foreach (array_values($ids) as $index => $id) {
                $user->experiences()->whereKey($id)->update(['order' => $index]);
            }
        });

        return $this->list($user);
    }

    public function findOwned(User $user, int $id): Experience
    {
        $experience = $user->experiences()->whereKey($id)->first();

        if (! $experience) {
            throw (new ModelNotFoundException)->setModel(Experience::class, [$id]);
        }

        return $experience;
    }
}
