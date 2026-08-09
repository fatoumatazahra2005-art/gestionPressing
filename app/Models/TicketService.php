<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketService extends Model
{
    use HasFactory;

    protected $table = 'ticket_services';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'ticket_id',
        'service_id',
        'quantite',
        'prix_unitaire',
    ];

    protected function casts(): array
    {
        return [
            'quantite' => 'integer',
            'prix_unitaire' => 'decimal:2',
        ];
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }


    public function sousTotal(): float
    {
        return (float) $this->prix_unitaire * $this->quantite;
    }
}
