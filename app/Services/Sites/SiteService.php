<?php

namespace App\Services\Sites;

use App\Repositories\Sites\SiteRepository;
use App\Models\Site;

class SiteService
{
    protected $repo;

    public function __construct(SiteRepository $repo)
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

    public function update(Site $site, array $data)
    {
        return $this->repo->update($site, $data);
    }

    public function delete(Site $site)
    {
        $this->repo->delete($site);
    }
}
