<?php

namespace App\Services\Agents;

use App\Repositories\Agents\AgentRepository;
use App\Models\Agent;

class AgentService
{
    protected $repo;

    public function __construct(AgentRepository $repo)
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

    public function update(Agent $agent, array $data)
    {
        return $this->repo->update($agent, $data);
    }

    public function delete(Agent $agent)
    {
        $this->repo->delete($agent);
    }
}
