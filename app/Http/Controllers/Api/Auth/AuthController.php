<?php
// app/Http/Controllers/Api/AuthController.php
namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Resources\Users\UserResource;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Tag(name="Auth")
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Auth"},
     *     summary="Connexion utilisateur",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"matricule","password"},
     *             @OA\Property(property="matricule", type="string", example="UIS00042"),
     *             @OA\Property(property="password", type="string", example="secret123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Connexion réussie"),
     *     @OA\Response(response=401, description="Identifiants invalides"),
     *     @OA\Response(response=403, description="Compte verrouillé")
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'matricule' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('matricule', $request->matricule)->first();

        if (!$user) {
            Log::warning("Tentative de connexion avec matricule inconnu: {$request->matricule}");
            return response()->json(['message' => 'Matricule ou mot de passe incorrect'], 401);
        }

        // Si l'utilisateur est verrouillé
        if ($user->is_locked) {
            return response()->json(['message' => 'Compte verrouillé. Contactez un administrateur.'], 403);
        }

        if (!Hash::check($request->password, $user->password)) {
            // Incrément du compteur
            $user->increment('login_attempts');

            Log::warning("Échec login #{$user->login_attempts} pour matricule: {$user->matricule}");

            // Verrouillage après 3 échecs
            if ($user->login_attempts >= 3) {
                $user->is_locked = true;
                $user->save();
                Log::warning("Compte verrouillé automatiquement: {$user->matricule}");
                return response()->json(['message' => 'Compte verrouillé après 3 tentatives échouées. Contactez un administrateur.'], 403);
            }

            return response()->json(['message' => 'Matricule ou mot de passe incorrect'], 401);
        }

        // Reset du compteur en cas de succès
        $user->login_attempts = 0;
        $user->save();

        // Création token
        $token = $user->createToken('api_token')->plainTextToken;
        Log::info("Connexion réussie pour {$user->matricule}");

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token,
        ]);
    }


    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Auth"},
     *     summary="Déconnexion utilisateur",
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Déconnecté avec succès")
     * )
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        $tokenId = $user->currentAccessToken()?->id ?? 'N/A';

        if ($user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
            Log::info("Token {$tokenId} supprimé pour l'utilisateur {$user->matricule}");
        }

        Log::info("Déconnexion réussie pour l'utilisateur: {$user->matricule}");

        return response()->json(['message' => 'Déconnecté avec succès']);
    }
}
