<?php

namespace App\Services;

use App\Models\Skill;
use App\Models\SkillCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SkillService
{
    /**
     * @return Collection<int, SkillCategory>
     */
    public function listCategories(User $user, bool $withSkills = true): Collection
    {
        $query = $user->skillCategories();

        if ($withSkills) {
            $query->with(['skills' => fn ($q) => $q->orderBy('order')]);
        }

        return $query->orderBy('order')->get();
    }

    public function createCategory(User $user, array $data): SkillCategory
    {
        $order = $data['order'] ?? ((int) $user->skillCategories()->max('order')) + 1;

        return $user->skillCategories()->create([
            'name' => $data['name'],
            'order' => $order,
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    public function updateCategory(User $user, int $id, array $data): SkillCategory
    {
        $category = $this->findOwnedCategory($user, $id);
        $category->update($data);

        return $category->fresh()->load(['skills' => fn ($q) => $q->orderBy('order')]);
    }

    public function deleteCategory(User $user, int $id): void
    {
        $this->findOwnedCategory($user, $id)->delete();
    }

    public function toggleCategory(User $user, int $id): SkillCategory
    {
        $category = $this->findOwnedCategory($user, $id);
        $category->update(['is_active' => ! $category->is_active]);

        return $category->fresh()->load(['skills' => fn ($q) => $q->orderBy('order')]);
    }

    /**
     * @param  array<int, int>  $ids
     * @return Collection<int, SkillCategory>
     */
    public function reorderCategories(User $user, array $ids): Collection
    {
        DB::transaction(function () use ($user, $ids) {
            foreach (array_values($ids) as $index => $id) {
                $user->skillCategories()->whereKey($id)->update(['order' => $index]);
            }
        });

        return $this->listCategories($user);
    }

    /**
     * @return Collection<int, Skill>
     */
    public function listSkills(User $user, ?int $categoryId = null): Collection
    {
        $query = $user->skills()->with('category')->orderBy('order');

        if ($categoryId !== null) {
            $query->where('category_id', $categoryId);
        }

        return $query->get();
    }

    public function createSkill(User $user, array $data): Skill
    {
        $this->findOwnedCategory($user, (int) $data['category_id']);

        $order = $data['order'] ?? ((int) $user->skills()
            ->where('category_id', $data['category_id'])
            ->max('order')) + 1;

        return $user->skills()->create([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'level' => $data['level'] ?? null,
            'level_type' => $data['level_type'],
            'is_featured' => $data['is_featured'] ?? false,
            'is_active' => $data['is_active'] ?? true,
            'order' => $order,
        ])->load('category');
    }

    public function updateSkill(User $user, int $id, array $data): Skill
    {
        $skill = $this->findOwnedSkill($user, $id);

        if (isset($data['category_id'])) {
            $this->findOwnedCategory($user, (int) $data['category_id']);
        }

        $skill->update($data);

        return $skill->fresh()->load('category');
    }

    public function deleteSkill(User $user, int $id): void
    {
        $this->findOwnedSkill($user, $id)->delete();
    }

    public function toggleSkill(User $user, int $id): Skill
    {
        $skill = $this->findOwnedSkill($user, $id);
        $skill->update(['is_active' => ! $skill->is_active]);

        return $skill->fresh()->load('category');
    }

    /**
     * @param  array<int, int>  $ids
     * @return Collection<int, Skill>
     */
    public function reorderSkills(User $user, array $ids, ?int $categoryId = null): Collection
    {
        DB::transaction(function () use ($user, $ids, $categoryId) {
            foreach (array_values($ids) as $index => $id) {
                $query = $user->skills()->whereKey($id);

                if ($categoryId !== null) {
                    $query->where('category_id', $categoryId);
                }

                $query->update(['order' => $index]);
            }
        });

        return $this->listSkills($user, $categoryId);
    }

    public function findOwnedCategory(User $user, int $id): SkillCategory
    {
        $category = $user->skillCategories()->whereKey($id)->first();

        if (! $category) {
            throw (new ModelNotFoundException)->setModel(SkillCategory::class, [$id]);
        }

        return $category;
    }

    public function findOwnedSkill(User $user, int $id): Skill
    {
        $skill = $user->skills()->whereKey($id)->first();

        if (! $skill) {
            throw (new ModelNotFoundException)->setModel(Skill::class, [$id]);
        }

        return $skill;
    }
}
