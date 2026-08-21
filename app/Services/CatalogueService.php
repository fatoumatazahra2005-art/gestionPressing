<?php

namespace App\Services;

use App\Models\Service;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class CatalogueService
{
    public function catalogueClient(?string $search = null): Collection
    {
        return Service::catalogue() ->when($search, function ($query) use ($search)
        { $query->where('libelle', 'ILIKE', "%{$search}%"); }) ->orderBy('libelle') ->get();
    }

    public function listAll(?string $search = null): Collection
    {
        return Service::query() ->when($search, function ($query) use ($search)
        { $query->where('libelle', 'ILIKE', "%{$search}%"); }) ->orderBy('libelle') ->get();
    }

    public function create(array $data): Service
    {
        return Service::create($data);
    }

    public function update(Service $service, array $data): Service
    {
        $service->update($data);

        return $service->refresh();
    }

    public function archive(Service $service): void
    {
        $service->update(['disponible' => false]);
    }

    public function reactivate(Service $service): void
    {
        $service->update(['disponible' => true]);
    }

    public function delete(Service $service): void
    {
        if (! $service->peutEtreSupprime()) {
            throw ValidationException::withMessages([
                'service' => ['Ce service est référencé dans des commandes existantes et ne peut pas être supprimé. Archivez-le plutôt.'],
            ]);
        }

        $service->delete();
    }
}
