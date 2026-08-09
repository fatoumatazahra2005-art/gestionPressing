<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'libelle',
        'prix_unitaire',
        'description',
        'disponible',
    ];

    protected function casts(): array
    {
        return [
            'prix_unitaire' => 'decimal:2',
            'disponible' => 'boolean',
        ];
    }

    public function ticketServices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TicketService::class);
    }

    public function scopeCatalogue(Builder $query): Builder
    {
        return $query->where('disponible', true);
    }


    public function peutEtreSupprime(): bool
    {
        return ! $this->ticketServices()->exists();
    }


}
