<?php

namespace App\Http\Controllers\Api\Sites;

use App\Http\Controllers\Controller;
use App\Http\Requests\SiteRequest;
use App\Http\Resources\Sites\SiteResource;
use App\Models\Site;
use App\Services\Sites\SiteService;

/**
 * @OA\Tag(name="Sites", description="Gestion des sites par le responsable")
 */
class SiteController extends Controller
{
    protected $service;

    public function __construct(SiteService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Get(
     *     path="/api/sites",
     *     tags={"Sites"},
     *     summary="Liste des sites",
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Liste des sites récupérée")
     * )
     */
    public function index()
    {
        return SiteResource::collection($this->service->list());
    }

    /**
     * @OA\Post(
     *     path="/api/sites",
     *     tags={"Sites"},
     *     summary="Créer un site",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","location","responsable_id"},
     *             @OA\Property(property="name", type="string", example="Site Centre"),
     *             @OA\Property(property="location", type="string", example="Yaoundé"),
     *             @OA\Property(property="responsable_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Site créé avec succès")
     * )
     */
    public function store(SiteRequest $request)
    {
        $site = $this->service->create($request->validated());
        return new SiteResource($site);
    }

    /**
     * @OA\Get(
     *     path="/api/sites/{site}",
     *     tags={"Sites"},
     *     summary="Afficher un site spécifique",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="site",
     *         in="path",
     *         required=true,
     *         description="ID du site",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Site récupéré avec succès"),
     *     @OA\Response(response=404, description="Site non trouvé")
     * )
     */
    public function show(Site $site)
    {
        return new SiteResource($this->service->get($site->id));
    }

    /**
     * @OA\Put(
     *     path="/api/sites/{site}",
     *     tags={"Sites"},
     *     summary="Mettre à jour un site",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="site",
     *         in="path",
     *         required=true,
     *         description="ID du site",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Site Nord"),
     *             @OA\Property(property="location", type="string", example="Douala"),
     *             @OA\Property(property="responsable_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Site mis à jour avec succès"),
     *     @OA\Response(response=404, description="Site non trouvé")
     * )
     */
    public function update(SiteRequest $request, Site $site)
    {
        $site = $this->service->update($site, $request->validated());
        return new SiteResource($site);
    }

    /**
     * @OA\Delete(
     *     path="/api/sites/{site}",
     *     tags={"Sites"},
     *     summary="Supprimer un site",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="site",
     *         in="path",
     *         required=true,
     *         description="ID du site",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Site supprimé avec succès"),
     *     @OA\Response(response=404, description="Site non trouvé")
     * )
     */
    public function destroy(Site $site)
    {
        $this->service->delete($site);
        return response()->json(['message' => 'Site supprimé avec succès']);
    }
}
