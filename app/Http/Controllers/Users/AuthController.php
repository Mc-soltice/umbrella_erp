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

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->service->register($request->validated());

        // Log::info("✅ Utilisateur {$user->matricule} connecté avec succès");

        return response()->json([
            'user'  => new UserResource($user),
        ]);
    }
    
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

    public function logout(Request $request)
    {
        $this->service->logout($request->user());
        return response()->json(['message' => 'Logged out successfully']);
    }
}
