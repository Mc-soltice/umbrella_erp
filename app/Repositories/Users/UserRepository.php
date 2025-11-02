<?php

namespace App\Repositories\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Exception;

class UserRepository
{
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function find($id): ?User
    {
        try {
            return $this->model->find($id);
        } catch (Exception $e) {
            Log::error('Erreur lors de la recherche de l’utilisateur', [
                'id' => $id,
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
    public function getAllUsers()
    {
        return User::orderBy('created_at', 'desc')->get();
    }

    public function findByMatricule(string $matricule): ?User
    {
        try {
            return $this->model->where('matricule', $matricule)->first();
        } catch (Exception $e) {
            Log::error('Erreur lors de la recherche par matricule', [
                'matricule' => $matricule,
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function findByEmail(string $email): ?User
    {
        try {
            return $this->model->where('email', $email)->first();
        } catch (Exception $e) {
            Log::error('Erreur lors de la recherche par email', [
                'email' => $email,
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }


    public function create(array $data): User
    {
        try {
            Log::info('Tentative de création d’un utilisateur', ['data' => $data]);

            $user = $this->model->create($data);

            Log::info('Utilisateur créé avec succès', ['id' => $user->id]);

            return $user;
        } catch (Exception $e) {
            Log::error('Erreur lors de la création d’un utilisateur', [
                'data' => $data,
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function update(User $user, array $data): User
    {
        try {
            Log::info('Tentative de mise à jour d’un utilisateur', [
                'user_id' => $user->id,
                'data' => $data,
            ]);

            $user->update($data);

            Log::info('Utilisateur mis à jour avec succès', [
                'user_id' => $user->id,
            ]);

            return $user->fresh();
        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour d’un utilisateur', [
                'user_id' => $user->id ?? null,
                'data' => $data,
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function delete(User $user): void
    {
        try {
            Log::info('Tentative de suppression d’un utilisateur', [
                'user_id' => $user->id,
            ]);

            $user->delete();

            Log::info('Utilisateur supprimé avec succès', [
                'user_id' => $user->id,
            ]);
        } catch (Exception $e) {
            Log::error('Erreur lors de la suppression d’un utilisateur', [
                'user_id' => $user->id ?? null,
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
