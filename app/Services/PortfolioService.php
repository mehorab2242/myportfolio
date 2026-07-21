<?php

namespace App\Services;

use App\Models\PortfolioCategory;
use App\Models\PortfolioItem;
use App\Models\PortfolioMedia;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PortfolioService
{
    /**
     * @return Collection<int, PortfolioCategory>
     */
    public function listCategories(User $user): Collection
    {
        return $user->portfolioCategories()->orderBy('order')->get();
    }

    public function createCategory(User $user, array $data): PortfolioCategory
    {
        $order = $data['order'] ?? ((int) $user->portfolioCategories()->max('order')) + 1;

        return $user->portfolioCategories()->create([
            'name' => $data['name'],
            'order' => $order,
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    public function updateCategory(User $user, int $id, array $data): PortfolioCategory
    {
        $category = $this->findOwnedCategory($user, $id);
        $category->update($data);

        return $category->fresh();
    }

    public function deleteCategory(User $user, int $id): void
    {
        $this->findOwnedCategory($user, $id)->delete();
    }

    public function toggleCategory(User $user, int $id): PortfolioCategory
    {
        $category = $this->findOwnedCategory($user, $id);
        $category->update(['is_active' => ! $category->is_active]);

        return $category->fresh();
    }

    /**
     * @param  array<int, int>  $ids
     * @return Collection<int, PortfolioCategory>
     */
    public function reorderCategories(User $user, array $ids): Collection
    {
        DB::transaction(function () use ($user, $ids) {
            foreach (array_values($ids) as $index => $id) {
                $user->portfolioCategories()->whereKey($id)->update(['order' => $index]);
            }
        });

        return $this->listCategories($user);
    }

    /**
     * @return Collection<int, PortfolioItem>
     */
    public function listItems(User $user, ?int $categoryId = null): Collection
    {
        $query = $user->portfolioItems()
            ->with(['category', 'media'])
            ->orderBy('order');

        if ($categoryId !== null) {
            $query->where('category_id', $categoryId);
        }

        return $query->get();
    }

    public function createItem(User $user, array $data): PortfolioItem
    {
        if (! empty($data['category_id'])) {
            $this->findOwnedCategory($user, (int) $data['category_id']);
        }

        $order = $data['order'] ?? ((int) $user->portfolioItems()->max('order')) + 1;

        return $user->portfolioItems()->create([
            'title' => $data['title'],
            'slug' => $this->uniqueSlug($user, $data['title']),
            'description' => $data['description'] ?? null,
            'short_description' => $data['short_description'] ?? null,
            'category_id' => $data['category_id'] ?? null,
            'client_name' => $data['client_name'] ?? null,
            'project_url' => $data['project_url'] ?? null,
            'start_date' => $data['start_date'] ?? null,
            'end_date' => $data['end_date'] ?? null,
            'is_featured' => $data['is_featured'] ?? false,
            'is_active' => $data['is_active'] ?? true,
            'order' => $order,
        ])->load(['category', 'media']);
    }

    public function updateItem(User $user, int $id, array $data): PortfolioItem
    {
        $item = $this->findOwnedItem($user, $id);

        if (array_key_exists('category_id', $data) && $data['category_id'] !== null) {
            $this->findOwnedCategory($user, (int) $data['category_id']);
        }

        if (isset($data['title']) && $data['title'] !== $item->title) {
            $data['slug'] = $this->uniqueSlug($user, $data['title'], $item->id);
        }

        $item->update($data);

        return $item->fresh()->load(['category', 'media']);
    }

    public function deleteItem(User $user, int $id): void
    {
        $item = $this->findOwnedItem($user, $id);

        foreach ($item->media as $media) {
            Storage::disk('public')->delete($media->file_path);
        }

        $item->delete();
    }

    public function toggleItem(User $user, int $id): PortfolioItem
    {
        $item = $this->findOwnedItem($user, $id);
        $item->update(['is_active' => ! $item->is_active]);

        return $item->fresh()->load(['category', 'media']);
    }

    /**
     * @param  array<int, int>  $ids
     * @return Collection<int, PortfolioItem>
     */
    public function reorderItems(User $user, array $ids): Collection
    {
        DB::transaction(function () use ($user, $ids) {
            foreach (array_values($ids) as $index => $id) {
                $user->portfolioItems()->whereKey($id)->update(['order' => $index]);
            }
        });

        return $this->listItems($user);
    }

    /**
     * @param  array<int, UploadedFile>  $files
     * @return Collection<int, PortfolioMedia>
     */
    public function storeMedia(User $user, int $itemId, array $files): Collection
    {
        $item = $this->findOwnedItem($user, $itemId);
        $order = (int) $item->media()->max('order');

        foreach ($files as $file) {
            $order++;
            $path = $this->putUploadedFile($file, "portfolio/{$user->id}/{$item->id}");

            $item->media()->create([
                'file_path' => $path,
                'order' => $order,
            ]);
        }

        return $item->fresh()->media()->orderBy('order')->get();
    }

    public function deleteMedia(User $user, int $mediaId): void
    {
        $media = $this->findOwnedMedia($user, $mediaId);
        Storage::disk('public')->delete($media->file_path);
        $media->delete();
    }

    /**
     * @param  array<int, int>  $ids
     * @return Collection<int, PortfolioMedia>
     */
    public function reorderMedia(User $user, int $itemId, array $ids): Collection
    {
        $item = $this->findOwnedItem($user, $itemId);

        DB::transaction(function () use ($item, $ids) {
            foreach (array_values($ids) as $index => $id) {
                $item->media()->whereKey($id)->update(['order' => $index]);
            }
        });

        return $item->fresh()->media()->orderBy('order')->get();
    }

    public function findOwnedCategory(User $user, int $id): PortfolioCategory
    {
        $category = $user->portfolioCategories()->whereKey($id)->first();

        if (! $category) {
            throw (new ModelNotFoundException)->setModel(PortfolioCategory::class, [$id]);
        }

        return $category;
    }

    public function findOwnedItem(User $user, int $id): PortfolioItem
    {
        $item = $user->portfolioItems()->whereKey($id)->first();

        if (! $item) {
            throw (new ModelNotFoundException)->setModel(PortfolioItem::class, [$id]);
        }

        return $item;
    }

    public function findOwnedMedia(User $user, int $id): PortfolioMedia
    {
        $media = PortfolioMedia::query()
            ->whereKey($id)
            ->whereHas('item', fn ($q) => $q->where('user_id', $user->id))
            ->first();

        if (! $media) {
            throw (new ModelNotFoundException)->setModel(PortfolioMedia::class, [$id]);
        }

        return $media;
    }

    private function uniqueSlug(User $user, string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'item';
        $slug = $base;
        $i = 2;

        while (
            $user->portfolioItems()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    /**
     * Store an uploaded file using its temp pathname directly.
     * Avoids UploadedFile::store() / getRealPath() issues under php-cgi on Windows.
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
}
