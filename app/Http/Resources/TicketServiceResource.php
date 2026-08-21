<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'service' => $this->whenLoaded('service', fn () => [
                'id' => $this->service->id,
                'libelle' => $this->service->libelle,
            ]),
            'quantite' => $this->quantite,
            'prix_unitaire' => (float) $this->prix_unitaire,
            'sous_total' => $this->sousTotal(),
        ];
    }
}
