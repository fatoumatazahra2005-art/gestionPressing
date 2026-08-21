<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PanierItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'service' => [
                'id' => $this->service->id,
                'nom' => $this->service->nom,
                'prix_unitaire' => $this->service->prix_unitaire,
            ],
            'sous_total' => $this->quantity * $this->service->prix_unitaire,
        ];
    }
}
