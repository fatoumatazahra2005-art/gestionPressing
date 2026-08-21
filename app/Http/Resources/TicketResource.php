<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'client' => $this->whenLoaded('client', fn () => [
                'id' => $this->client->id,
                'name' => $this->client->name,
            ]),
            'statut' => $this->statut,
            'date_depot' => $this->date_depot,
            'date_recuperation' => $this->date_recuperation,
            'montant_total' => (float) $this->montant_total,
            'est_paye' => $this->estPaye(),
            'lignes' => TicketServiceResource::collection($this->whenLoaded('ticketServices')),
            'paiement' => $this->whenLoaded('paiement', fn () => $this->paiement ? new PaiementResource($this->paiement) : null),
        ];
    }
}
