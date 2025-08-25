<?php


namespace App\Repositories\Users;

use App\Models\User;

class UserRepository
{
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function findByMatricule(string $matricule): ?User
    {
        return User::where('matricule', $matricule)->first();
    }


    public function getAll()
    {
        return User::with('roles', 'permissions')->get();
    }

    public function find($id): ?User
    {
        return User::with('roles', 'permissions')->find($id);
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user;
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }
}
