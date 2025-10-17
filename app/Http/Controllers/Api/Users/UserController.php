<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\Users\UserResource;
use App\Repositories\Users\UserRepository;
use App\Services\Users\UserService;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Users")
 */
class UserController extends Controller
{
    protected $service;
    protected $repo;

    public function __construct(UserService $service, UserRepository $repo)
    {
        $this->service = $service;
        $this->repo = $repo;

    }

    /**
     * @OA\Get(
     *     path="/api/users",
     *     tags={"Users"},
     *     summary="Lister les utilisateurs",
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Liste paginée")
     * )
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        $users = $this->repo->paginate($perPage);
        return UserResource::collection($users);
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     tags={"Users"},
     *     summary="Créer un utilisateur",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(@OA\JsonContent(ref="#/components/schemas/UserRequest")),
     *     @OA\Response(response=201, description="Utilisateur créé")
     * )
     */
    public function store(UserRequest $request)
    {
        $user = $this->service->create($request->validated());
        return new UserResource($user);
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Voir un utilisateur",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Détails")
     * )
     */
    public function show(User $user)
    {
        $user->load('roles');
        return new UserResource($user);
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Mettre à jour un utilisateur",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(@OA\JsonContent(ref="#/components/schemas/UserRequest")),
     *     @OA\Response(response=200, description="Utilisateur mis à jour")
     * )
     */
    public function update(UserRequest $request, User $user)
    {
        // log::info('Test');
        // return response()->json(['message' => 'Test']);
        $updated = $this->service->update($user, $request->validated());
        return new UserResource($updated);
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Supprimer un utilisateur",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=204, description="Utilisateur supprimé")
     * )
     */
    public function destroy(User $user)
    {
        $this->service->delete($user);
        return response()->noContent();
    }

}