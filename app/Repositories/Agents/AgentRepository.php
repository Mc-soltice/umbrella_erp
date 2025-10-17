<?php

namespace App\Repositories\Agents;

use App\Models\Agent;
use Illuminate\Support\Facades\Log;

class AgentRepository
{
    public function all()
    {
        Log::info("Récupération de tous les agents");
        return Agent::all();
    }

    public function find(int $id)
    {
        Log::info("Récupération de l'agent ID: {$id}");
        return Agent::findOrFail($id);
    }

    public function create(array $data)
    {
        $agent = Agent::create($data);
        Log::info("Création agent ID: {$agent->id}, matricule: {$agent->matricule}");
        return $agent;
    }

    public function update(Agent $agent, array $data)
    {
        $agent->update($data);
        Log::info("Mise à jour agent ID: {$agent->id}");
        return $agent;
    }

    public function delete(Agent $agent)
    {
        $agent->delete();
        Log::info("Suppression agent ID: {$agent->id}");
    }
}
