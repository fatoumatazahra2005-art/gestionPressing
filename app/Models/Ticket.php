<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ticket extends Model
{
    use HasFactory;

    public const STATUT_RECU = 'recu';
    public const STATUT_EN_TRAITEMENT = 'en_traitement';
    public const STATUT_PRET = 'pret';
    public const STATUT_RECUPERE = 'recupere';
    public const STATUT_ANNULE = 'annule';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'client_id',
        'statut',
        'date_depot',
        'date_recuperation',
        'montant_total',
    ];

    protected function casts(): array
    {
        return [
            'date_depot' => 'datetime',
            'date_recuperation' => 'datetime',
            'montant_total' => 'decimal:2',
        ];
    }


    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function ticketServices(): HasMany
    {
        return $this->hasMany(TicketService::class);
    }

    public function paiement(): HasOne
    {
        return $this->hasOne(Paiement::class);
    }


    public function scopeDuJour(Builder $query): Builder
    {
        return $query->whereDate('date_depot', today());
    }

    public function scopeVisiblePar(Builder $query, User $utilisateur): Builder
    {
        if ($utilisateur->estGestionnaire()) {
            return $query;
        }

        return $query->where('client_id', $utilisateur->id);
    }


    public function estPaye(): bool
    {
        return $this->paiement()->exists();
    }


    public function peutEtreRecupere(): bool
    {
        return $this->statut === self::STATUT_PRET && $this->estPaye();
    }


    public function peutEtreAnnule(): bool
    {
        return in_array($this->statut, [self::STATUT_RECU, self::STATUT_EN_TRAITEMENT], true);
    }


    public function recalculerMontant(): void
    {
        $total = $this->ticketServices()
            ->get()
            ->sum(fn (TicketService $ligne) => $ligne->sousTotal());

        $this->update(['montant_total' => $total]);
    }
}
