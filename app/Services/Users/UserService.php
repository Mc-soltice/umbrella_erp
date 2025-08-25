<?php

namespace App\Services\Users;

use App\Repositories\Users\UserRepository;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserService
{
    protected $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->getAll()->load('roles', 'permissions');
    }

    public function find($id)
    {
        return $this->repository->find($id)->load('roles', 'permissions');
    }

    public function create(array $data): User
    {
        return $this->repository->create($data);
    }

    public function update(User $user, array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return $this->repository->update($user, $data);
    }

    public function delete(User $user): bool
    {
        return $this->repository->delete($user);
    }
}
