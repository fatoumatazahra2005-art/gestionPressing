<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Panier extends Model
{
    protected $fillable = [
        'utilisateur_id',
    ];

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PanierItem::class);
    }

    public function total(): float
    {
        return $this->items->sum(fn (PanierItem $item) => $item->quantity * $item->service->prix_unitaire);
    }

    public function count(): int
    {
        return $this->items->sum('quantity');
    }
}
