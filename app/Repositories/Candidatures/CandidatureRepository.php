<?php

namespace App\Repositories\Candidatures;

use App\Models\Candidature;
use Illuminate\Support\Facades\Log;

class CandidatureRepository
{

    
    public function all()
    {
        Log::info("Récupération de toutes les candidatures");
        return Candidature::all();
    }

    public function find(int $id)
    {
        Log::info("Récupération de la candidature ID: {$id}");
        return Candidature::findOrFail($id);
    }

    public function create(array $data)
    {
        $candidature = Candidature::create($data);
        Log::info("Création candidature ID: {$candidature->id}");
        return $candidature;
    }

    public function update(Candidature $candidature, array $data)
    {
        $candidature->update($data);
        Log::info("Mise à jour candidature ID: {$candidature->id}");
        return $candidature;
    }

    public function delete(Candidature $candidature)
    {
        $candidature->delete();
        Log::info("Suppression candidature ID: {$candidature->id}");
    }
}
