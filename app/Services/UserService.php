<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

readonly class UserService
{
    public function __construct(
        private UserRepositoryInterface $users,
    ) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->users->paginate($perPage);
    }

    public function find(int $id): ?User
    {
        return $this->users->find($id);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): User
    {
        $data['password'] = bcrypt($data['password']);

        return $this->users->create($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(User $user, array $data): User
    {
        if (array_key_exists('password', $data) && $data['password']) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        return $this->users->update($user, $data);
    }

    public function delete(User $user): bool
    {
        return $this->users->delete($user);
    }
}
