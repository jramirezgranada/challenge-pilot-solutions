<?php

namespace App\Repositories;

use App\Models\Article;
use App\Repositories\Contracts\ArticleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentArticleRepository implements ArticleRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Article::query()
            ->with(['author', 'categories'])
            ->latest()
            ->paginate($perPage);
    }

    public function find(int $id): ?Article
    {
        return Article::query()->with(['author', 'categories'])->find($id);
    }

    public function create(array $attributes, Collection $categoryIds): Article
    {
        $article = Article::query()->create($attributes);
        $article->categories()->sync($categoryIds);

        return $article->load(['author', 'categories']);
    }

    public function update(Article $article, array $attributes, ?Collection $categoryIds = null): Article
    {
        $article->update($attributes);

        if ($categoryIds !== null) {
            $article->categories()->sync($categoryIds);
        }

        return $article->load(['author', 'categories']);
    }

    public function delete(Article $article): bool
    {
        return (bool) $article->delete();
    }

    public function slugExists(string $slug, ?int $ignoreId = null): bool
    {
        return Article::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists();
    }
}
