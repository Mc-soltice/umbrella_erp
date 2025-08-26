<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UserToggleLockRequest
 *
 * Cette requête gère la validation pour l'action de bascule (bloquer/débloquer) d'un utilisateur.
 * Comme il s'agit uniquement de basculer l'état, aucune donnée supplémentaire n'est requise.
 */
class UserToggleLockRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à effectuer cette requête.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // ✅ tu peux mettre une logique si besoin (policy)
    }

    /**
     * Règles de validation pour la requête.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'matricule' => 'required|string|exists:users,matricule'
        ];
    }

        /**
     * Retourne le matricule validé
     *
     * @return string
     */
    public function getMatricule(): string
    {
        return $this->validated()['matricule'];
    }
}
