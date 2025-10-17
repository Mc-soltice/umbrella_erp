<?php

namespace App\Repositories\Sites;

use App\Models\Site;
use Illuminate\Support\Facades\Log;

class SiteRepository
{
    public function all()
    {
        Log::info("Récupération de tous les sites");
        return Site::all();
    }

    public function find(int $id)
    {
        Log::info("Récupération du site ID: {$id}");
        return Site::findOrFail($id);
    }

    public function create(array $data)
    {
        $site = Site::create($data);
        Log::info("Création du site ID: {$site->id}");
        return $site;
    }

    public function update(Site $site, array $data)
    {
        $site->update($data);
        Log::info("Mise à jour du site ID: {$site->id}");
        return $site;
    }

    public function delete(Site $site)
    {
        $site->delete();
        Log::info("Suppression du site ID: {$site->id}");
    }
}
