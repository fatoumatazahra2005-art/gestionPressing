<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'libelle' => $this->libelle,
            'description' => $this->description,
            'prix_unitaire' => (float) $this->prix_unitaire,
            'disponible' => $this->disponible,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
