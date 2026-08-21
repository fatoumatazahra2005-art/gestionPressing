<?php

namespace App\Services;

use App\Models\Paiement;
use App\Models\Ticket;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StatistiquesService
{

    public function statsDuJour(): array
    {
        $aujourdhui = Carbon::today();

        $ticketsCrees = Ticket::whereDate('date_depot', $aujourdhui)->count();

        $ticketsRecuperes = Ticket::where('statut', Ticket::STATUT_RECUPERE)
            ->whereDate('date_recuperation', $aujourdhui)
            ->count();

        $recette = Paiement::whereDate('date_paiement', $aujourdhui)->sum('montant');

        return [
            'tickets_crees' => $ticketsCrees,
            'tickets_recuperes' => $ticketsRecuperes,
            'recette_journaliere' => (float) $recette,
        ];
    }


    public function ticketsParMois(int $mois = 12): array
    {
        $debut = Carbon::now()->subMonths($mois - 1)->startOfMonth();

        $resultats = Ticket::selectRaw("DATE_FORMAT(date_depot, '%Y-%m') as mois, COUNT(*) as total")
            ->where('date_depot', '>=', $debut)
            ->groupBy('mois')
            ->orderBy('mois')
            ->get();


        return $this->completerMoisManquants($resultats->keyBy('mois')->map->total->toArray(), $mois);
    }


    public function chiffreAffairesParServicePourMois(int $mois = 6): array
    {
        $debut = Carbon::now()->subMonths($mois - 1)->startOfMonth();

        return DB::table('paiements')
            ->join('tickets', 'paiements.ticket_id', '=', 'tickets.id')
            ->join('ticket_services', 'ticket_services.ticket_id', '=', 'tickets.id')
            ->join('services', 'ticket_services.service_id', '=', 'services.id')
            ->selectRaw("
                DATE_FORMAT(paiements.date_paiement, '%Y-%m') as mois,
                services.libelle as service,
                SUM(ticket_services.quantite * ticket_services.prix_unitaire) as total
            ")
            ->where('paiements.date_paiement', '>=', $debut)
            ->groupBy('mois', 'services.libelle')
            ->orderBy('mois')
            ->get()
            ->map(fn ($ligne) => [
                'mois' => $ligne->mois,
                'service' => $ligne->service,
                'total' => (float) $ligne->total,
            ])
            ->toArray();
    }


    private function completerMoisManquants(array $donnees, int $mois): array
    {
        $resultat = [];
        $curseur = Carbon::now()->subMonths($mois - 1)->startOfMonth();

        for ($i = 0; $i < $mois; $i++) {
            $cle = $curseur->format('Y-m');
            $resultat[] = [
                'mois' => $cle,
                'total' => $donnees[$cle] ?? 0,
            ];
            $curseur->addMonth();
        }

        return $resultat;
    }
}
