<?php

namespace App\Services\Users;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Users\UserRepository;

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

        /**
     * Bascule l'état de verrouillage (is_locked) d'un utilisateur.
     * - Si l'utilisateur est actuellement actif (is_locked = false), il sera bloqué (is_locked = true).
     * - Si l'utilisateur est actuellement bloqué (is_locked = true), il sera réactivé (is_locked = false).
     *
     * @param User $user
     * @return User
     */
    public function toggleLock($matricule): ?User
    {
        $user = $this->repository->findByMatricule($matricule);
        $newStatus = !$user->is_locked;
        $updatedUser = $this->repository->update($user, [
            'is_locked' => $newStatus
        ]);

        // 🔥 Loguer l'événement
        activity('user_management')
            ->causedBy(auth()->user()) // utilisateur qui effectue l'action
            ->performedOn($user)       // utilisateur cible
            ->withProperties([
                'user_id' => $user->id,
                'email'   => $user->email,
                'locked'  => $newStatus,
            ])
            ->log($newStatus ? 'Utilisateur bloqué' : 'Utilisateur débloqué');

        return $updatedUser;
    }
}
