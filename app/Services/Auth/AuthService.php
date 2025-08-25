<?php
namespace App\Services\Auth;

use App\Repositories\Users\UserRepository;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthService
{
    protected $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function register(array $data): User
    {
        $data['matricule'] = 'UIS' . rand(1000, 9999);
        $data['password'] = Hash::make($data['password']);

        $user = $this->repository->create($data);

        if (isset($data['role'])) {
            $user->assignRole($data['role']);
        }

        return $user->load('roles', 'permissions');

    }

    public function login(array $data): ?array
    {
        $user = $this->repository->findByMatricule($data['matricule']);

        if (!$user || !Hash::check($data['password'], $user->password)) {
            if ($user) {
                $attempt = $user->loginAttempt()->firstOrCreate([]);
                $attempt->increment('attempts');
            }
            return null;
        }
        
        /***** reset les tentatives en cas de succès */
        $user->loginAttempt()->updateOrCreate([], [
            'attempts' => 0,
            'locked_until' => null,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function logout(User $user): void
    {
        $user->tokens()->delete();
    }
}
