<?php

namespace App\Services\Candidatures;

use App\Repositories\Candidatures\CandidatureRepository;
use App\Models\Candidature;
use App\Models\Agent;
use App\Models\Site;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CandidatureService
{
    protected $repo;

    public function __construct(CandidatureRepository $repo)
    {
        $this->repo = $repo;
    }

    public function list()
    {
        return $this->repo->all();
    }

    public function get(int $id)
    {
        return $this->repo->find($id);
    }

    public function create(array $data)
    {
        return $this->repo->create($data);
    }

    public function update(Candidature $candidature, array $data)
    {
        return $this->repo->update($candidature, $data);
    }

    public function delete(Candidature $candidature)
    {
        $this->repo->delete($candidature);
    }

    public function validateCandidature(Candidature $candidature, Site $site)
    {
        // Valider la candidature
        $candidature->status = 'validated';
        $candidature->save();
        Log::info("Candidature ID {$candidature->id} validée");

        // Créer un agent
        $agent = Agent::create([
            'matricule' => 'AGT'.rand(10000, 99999),
            'first_name' => $candidature->first_name,
            'last_name'  => $candidature->last_name,
            'location'   => $candidature->location,
            'phone'      => $candidature->phone,
            'email'      => $candidature->email,
            'site_id'    => $site->id,
            'password'   => bcrypt(Str::random(10)) // mot de passe temporaire
        ]);
        Log::info("Agent créé à partir de la candidature ID {$candidature->id}, matricule {$agent->matricule}");

        return $agent;
    }
}
