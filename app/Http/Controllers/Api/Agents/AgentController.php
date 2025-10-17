<?php

namespace App\Http\Controllers\Api\Agents;

use App\Http\Controllers\Controller;
use App\Http\Requests\AgentRequest;
use App\Http\Resources\Agents\AgentResource;
use App\Models\Agent;
use App\Services\Agents\AgentService;

class AgentController extends Controller
{
    protected $service;

    public function __construct(AgentService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Get(
     *     path="/api/agent",
     *     tags={"Agents"},
     *     summary="Liste de tous les agents",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des agents",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Agent"))
     *     )
     * )
     */
    public function index()
    {
        return AgentResource::collection($this->service->list());
    }

    /**
     * @OA\Post(
     *     path="/api/agent",
     *     tags={"Agents"},
     *     summary="Créer un nouvel agent",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AgentRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Agent créé avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/Agent")
     *     )
     * )
     */
    public function store(AgentRequest $request)
    {
        $agent = $this->service->create($request->validated());
        return new AgentResource($agent);
    }

    /**
     * @OA\Get(
     *     path="/api/agent/{id}",
     *     tags={"Agents"},
     *     summary="Afficher un agent spécifique",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="agent",
     *         in="path",
     *         description="ID de l'agent",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de l'agent",
     *         @OA\JsonContent(ref="#/components/schemas/Agent")
     *     )
     * )
     */
    public function show(Agent $agent)
    {
        return new AgentResource($this->service->get($agent->id));
    }

    /**
     * @OA\Put(
     *     path="/api/agent/{id}",
     *     tags={"Agents"},
     *     summary="Mettre à jour un agent",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="agent",
     *         in="path",
     *         description="ID de l'agent",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AgentRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Agent mis à jour avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/Agent")
     *     )
     * )
     */
    public function update(AgentRequest $request, Agent $agent)
    {
        $agent = $this->service->update($agent, $request->validated());
        return new AgentResource($agent);
    }

    /**
     * @OA\Delete(
     *     path="/api/agent/{id}",
     *     tags={"Agents"},
     *     summary="Supprimer un agent",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="agent",
     *         in="path",
     *         description="ID de l'agent",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Agent supprimé avec succès"
     *     )
     * )
     */
    public function destroy(Agent $agent)
    {
        $this->service->delete($agent);
        return response()->json(['message' => 'Agent supprimé avec succès']);
    }
}
