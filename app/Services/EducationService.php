<?php

namespace App\Services;

use App\Models\Education;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EducationService
{
    /**
     * @return Collection<int, Education>
     */
    public function list(User $user): Collection
    {
        return $user->educations()->orderBy('order')->get();
    }

    public function create(User $user, array $data): Education
    {
        $order = $data['order'] ?? ((int) $user->educations()->max('order')) + 1;
        $isCurrent = (bool) ($data['is_current'] ?? false);

        return $user->educations()->create([
            'degree' => $data['degree'],
            'institution' => $data['institution'],
            'field_of_study' => $data['field_of_study'] ?? null,
            'location' => $data['location'] ?? null,
            'start_date' => $data['start_date'] ?? null,
            'end_date' => $isCurrent ? null : ($data['end_date'] ?? null),
            'is_current' => $isCurrent,
            'grade' => $data['grade'] ?? null,
            'description' => $data['description'] ?? null,
            'is_active' => $data['is_active'] ?? true,
            'order' => $order,
        ]);
    }

    public function update(User $user, int $id, array $data): Education
    {
        $education = $this->findOwned($user, $id);

        if (array_key_exists('is_current', $data) && $data['is_current']) {
            $data['end_date'] = null;
        }

        $education->update($data);

        return $education->fresh();
    }

    public function delete(User $user, int $id): void
    {
        $this->findOwned($user, $id)->delete();
    }

    public function toggle(User $user, int $id): Education
    {
        $education = $this->findOwned($user, $id);
        $education->update(['is_active' => ! $education->is_active]);

        return $education->fresh();
    }

    /**
     * @param  array<int, int>  $ids
     * @return Collection<int, Education>
     */
    public function reorder(User $user, array $ids): Collection
    {
        DB::transaction(function () use ($user, $ids) {
            foreach (array_values($ids) as $index => $id) {
                $user->educations()->whereKey($id)->update(['order' => $index]);
            }
        });

        return $this->list($user);
    }

    public function findOwned(User $user, int $id): Education
    {
        $education = $user->educations()->whereKey($id)->first();

        if (! $education) {
            throw (new ModelNotFoundException)->setModel(Education::class, [$id]);
        }

        return $education;
    }
}
