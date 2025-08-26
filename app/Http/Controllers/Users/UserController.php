<?php
namespace App\Http\Controllers\Users;

use App\Services\Users\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UserRequest;
use App\Http\Resources\Users\UserResource;
use App\Http\Requests\Users\UserToggleLockRequest;
use App\Models\User;

class UserController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return UserResource::collection($this->service->getAll());
    }


    public function show($id)
    {
        $user = $this->service->find($id);
        return $user ? new UserResource($user) : response()->json(['message' => 'Not found'], 404);
    }

    public function update(UserRequest $request, User $user)
    {
        $user = $this->service->update($user, $request->validated());
        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        $this->service->delete($user);
        return response()->json(['message' => 'Deleted successfully']);
    }

    /**
     * Bloque ou active un utilisateur selon son état actuel
     *
     * @param \App\Http\Requests\Users\UserToggleLockRequest $request
     * @return \Illuminate\Http\JsonResponse|\App\Http\Resources\Users\UserResource
     */
    public function toggleLock(UserToggleLockRequest $request)
    {
        // On récupère le matricule depuis la requête
        $matricule = $request->matricule;

        // Appel au service pour switcher l'état
        $user = $this->service->toggleLock($matricule);

        // Si utilisateur introuvable
        if (!$user) {
            return response()->json(['message' => 'Utilisateur introuvable'], 404);
        }

        // Détermination du statut actuel
        $status = $user->is_locked ? 'bloqué' : 'activé';

        // Réponse finale
        return response()->json([
            'message' => "Utilisateur {$user->first_name} {$user->last_name} est maintenant {$status}",
            'user' => new UserResource($user),
        ]);
    }


    /**
     * Récupère l'historique d'activité d'un utilisateur.
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function activity(User $user)
    {
        return response()->json($user->activities); // relation automatique fournie par Spatie
    }


}
