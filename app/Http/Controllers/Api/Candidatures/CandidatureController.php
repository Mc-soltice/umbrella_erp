<?php

namespace App\Http\Controllers\Api\Candidatures;

use App\Http\Controllers\Controller;
use App\Http\Requests\CandidatureRequest;
use App\Http\Resources\Candidatures\CandidatureResource;
use App\Models\Candidature;
use App\Models\Site;
use App\Services\Candidatures\CandidatureService;
use Illuminate\Http\Request;


/**
 * @OA\Tag(
 *     name="Candidatures",
 *     description="Gestion des candidatures pour les agents"
 * )
 */
class CandidatureController extends Controller
{
    protected $service;

    public function __construct(CandidatureService $service)
    {
        $this->service = $service;
    }


    /**
     * @OA\Get(
     *     path="/api/candidatures",
     *     tags={"Candidatures"},
     *     summary="Récupérer toutes les candidatures",
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Liste des candidatures récupérée avec succès")
     * )
     */
    public function index()
    {
        return CandidatureResource::collection($this->service->list());
    }


    /**
     * @OA\Post(
     *     path="/api/candidatures",
     *     tags={"Candidatures"},
     *     summary="Créer une nouvelle candidature",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"first_name","last_name","location","email"},
     *             @OA\Property(property="first_name", type="string", example="John"),
     *             @OA\Property(property="last_name", type="string", example="Doe"),
     *             @OA\Property(property="location", type="string", example="Yaoundé"),
     *             @OA\Property(property="phone", type="string", example="+237699123456"),
     *             @OA\Property(property="email", type="string", example="john.doe@example.com")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Candidature créée avec succès"),
     *     @OA\Response(response=422, description="Validation des champs échouée")
     * )
     */
    public function store(CandidatureRequest $request)
    {
        $candidature = $this->service->create($request->validated());
        return new CandidatureResource($candidature);
    }



    /**
     * @OA\Get(
     *     path="/api/candidatures/{candidature}",
     *     tags={"Candidatures"},
     *     summary="Récupérer une candidature par ID",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="candidature",
     *         in="path",
     *         description="ID de la candidature",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Candidature récupérée avec succès"),
     *     @OA\Response(response=404, description="Candidature non trouvée")
     * )
     */

    public function show(Candidature $candidature)
    {
        return new CandidatureResource($this->service->get($candidature->id));
    }



    /**
     * @OA\Put(
     *     path="/api/candidatures/{candidature}",
     *     tags={"Candidatures"},
     *     summary="Mettre à jour une candidature",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="candidature",
     *         in="path",
     *         description="ID de la candidature",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="first_name", type="string", example="John"),
     *             @OA\Property(property="last_name", type="string", example="Doe"),
     *             @OA\Property(property="location", type="string", example="Douala"),
     *             @OA\Property(property="phone", type="string", example="+237699123456"),
     *             @OA\Property(property="email", type="string", example="john.doe@example.com")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Candidature mise à jour avec succès"),
     *     @OA\Response(response=404, description="Candidature non trouvée"),
     *     @OA\Response(response=422, description="Validation échouée")
     * )
     */

    public function update(CandidatureRequest $request, Candidature $candidature)
    {
        $candidature = $this->service->update($candidature, $request->validated());
        return new CandidatureResource($candidature);
    }


    /**
     * @OA\Delete(
     *     path="/api/candidatures/{candidature}",
     *     tags={"Candidatures"},
     *     summary="Supprimer une candidature",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="candidature",
     *         in="path",
     *         description="ID de la candidature",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Candidature supprimée avec succès"),
     *     @OA\Response(response=404, description="Candidature non trouvée")
     * )
     */
    public function destroy(Candidature $candidature)
    {
        $this->service->delete($candidature);
        return response()->json(['message' => 'Candidature supprimée avec succès']);
    }

    /**
     * @OA\Post(
     *     path="/api/candidatures/{candidature}/validate",
     *     tags={"Candidatures"},
     *     summary="Valider une candidature et créer un agent",
     *     @OA\Parameter(
     *         name="candidature",
     *         in="path",
     *         description="ID de la candidature",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="site_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Candidature validée et agent créé")
     * )
     */

    public function validateCandidature(Candidature $candidature, Request $request)
    {
        $request->validate(['site_id' => 'required|exists:sites,id']);
        $site = Site::findOrFail($request->site_id);

        $agent = $this->service->validateCandidature($candidature, $site);

        return response()->json([
            'message' => 'Candidature validée et agent créé',
            'agent' => $agent
        ]);
    }
}
