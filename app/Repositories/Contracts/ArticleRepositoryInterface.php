<?php

namespace App\Repositories\Contracts;

use App\Models\Article;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ArticleRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?Article;

    public function create(array $attributes, Collection $categoryIds): Article;

    public function update(Article $article, array $attributes, ?Collection $categoryIds = null): Article;

    public function delete(Article $article): bool;

    public function slugExists(string $slug, ?int $ignoreId = null): bool;
}
