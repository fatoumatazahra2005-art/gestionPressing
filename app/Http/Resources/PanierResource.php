<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PanierResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'items' => PanierItemResource::collection($this->whenLoaded('items')),
            'total' => $this->total(),
            'count' => $this->count(),
        ];
    }
}
