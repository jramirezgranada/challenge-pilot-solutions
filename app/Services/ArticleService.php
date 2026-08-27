<?php

namespace App\Services;

use App\Exceptions\UserNotActiveException;
use App\Models\Article;
use App\Models\User;
use App\Repositories\Contracts\ArticleRepositoryInterface;
use App\Support\SlugGenerator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

readonly class ArticleService
{
    public function __construct(
        private ArticleRepositoryInterface $articles,
    ) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->articles->paginate($perPage);
    }

    public function find(int $id): ?Article
    {
        return $this->articles->find($id);
    }

    public function create(User $author, array $data, array $categoryIds): Article
    {
        $this->ensureAuthorIsActive($author);

        $data['user_id'] = $author->id;

        return $this->articles->create($data, collect($categoryIds));
    }

    public function update(Article $article, User $editor, array $data, ?array $categoryIds = null): Article
    {
        $this->ensureAuthorIsActive($editor);

        return $this->articles->update($article, $data, $categoryIds !== null ? collect($categoryIds) : null);
    }

    public function delete(Article $article): bool
    {
        return $this->articles->delete($article);
    }

    private function ensureAuthorIsActive(User $user): void
    {
        if (! $user->isActive()) {
            throw new UserNotActiveException;
        }
    }
}
