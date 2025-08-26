<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Services\Auth\AuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Users\UserResource;
use App\Http\Requests\Auth\RegisterRequest;
// use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $service;

    /**
     * Initialise le contrôleur d'authentification avec le service Auth.
     *
     * @param AuthService $service Service d'authentification
     */
    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    /**
     * Inscrit un nouvel utilisateur.
     *
     * @param RegisterRequest $request Requête de validation des données d'inscription
     * @return \Illuminate\Http\JsonResponse Utilisateur créé au format JSON
     */
    public function register(RegisterRequest $request)
    {
        $user = $this->service->register($request->validated());

        // Log::info("✅ Utilisateur {$user->matricule} connecté avec succès");

        return response()->json([
            'user'  => new UserResource($user),
        ]);
    }
    
    /**
     * Authentifie un utilisateur et retourne son token d'accès.
     *
     * @param LoginRequest $request Requête de validation des données de connexion
     * @return \Illuminate\Http\JsonResponse Utilisateur et token ou erreur d'authentification
     */
    public function login(LoginRequest $request)
    {
        $credentials = $this->service->login($request->validated());
        
        if (!$credentials) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Log::info("✅ Utilisateur {$request->matricule} connecté avec succès");
        
        return response()->json([
            'user'  => new UserResource($credentials['user']),
            'token' => $credentials['token'],
        ]);
    }

    /**
     * Déconnecte l'utilisateur actuellement authentifié.
     *
     * @param Request $request Requête HTTP contenant l'utilisateur authentifié
     * @return \Illuminate\Http\JsonResponse Message de confirmation de déconnexion
     */
    public function logout(Request $request)
    {
        $this->service->logout($request->user());
        return response()->json(['message' => 'Logged out successfully']);
    }
}