<?php
// app/Services/UserService.php
namespace App\Services\Users;

use App\Repositories\Users\UserRepository;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserService
{
    protected $repo;

    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

    protected function generateMatricule(): string
    {
        // Génère UIS + 5 chiffres ; vérifie unicité
        do {
            $digits = random_int(0, 99999);
            $mat = sprintf('UIS%05d', $digits);
        } while ($this->repo->findByMatricule($mat) !== null);

        return $mat;
    }

        public function getAllUsers()
    {
        return $this->repo->getAllUsers();
    }

    public function create(array $data): User
    {
        // préparation des données
        $data['matricule'] = $this->generateMatricule();
        $data['password'] = Hash::make($data['password']);

        $user = $this->repo->create($data);

        // assignation rôle (si fourni)
        if (!empty($data['role'])) {
            // crée le rôle s'il n'existe pas (optionnel)
            $role = Role::firstOrCreate(['name' => $data['role']]);
            $user->assignRole($role);
        }

        return $user;
    }

    public function update(User $user, array $data): User
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user = $this->repo->update($user, $data);

        if (array_key_exists('role', $data)) {
            $user->syncRoles($data['role'] ? [$data['role']] : []);
        }

        return $user;
    }

    public function delete(User $user): void
    {
        $this->repo->delete($user);
    }
}
