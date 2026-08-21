<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Mail\CommandeAnnuleeMail;
use App\Mail\NewOrderMail;
use App\Mail\TicketConfirmationMail;
use App\Mail\TicketReceiptMail;
use App\Models\Paiement;
use App\Models\Service as ServiceModel;
use App\Models\Ticket;
use App\Models\TicketService as TicketServiceLine;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TicketWorkFlowService
{
    private const RELATIONS = ['client', 'ticketServices.service', 'paiement'];

    public function creer(array $lignes, User $client): Ticket
    {
        $ticket = DB::transaction(function () use ($lignes, $client) {

            $ticket = Ticket::create([
                'client_id' => $client->id,
                'statut' => Ticket::STATUT_RECU,
                'date_depot' => now(),
                'montant_total' => 0,
            ]);

            foreach ($lignes as $ligne) {
                $service = ServiceModel::catalogue()->findOrFail($ligne['service_id']);

                TicketServiceLine::create([
                    'ticket_id' => $ticket->id,
                    'service_id' => $service->id,
                    'quantite' => $ligne['quantite'],
                    'prix_unitaire' => $service->prix_unitaire,
                ]);
            }

            $ticket->recalculerMontant();

            return $ticket->fresh(self::RELATIONS);
        });

        $this->notifierDepot($ticket);

        return $ticket;
    }

    public function passerEnTraitement(Ticket $ticket): Ticket
    {
        if ($ticket->statut !== Ticket::STATUT_RECU) {
            throw new ApiException("Seul un ticket 'recu' peut passer 'en traitement'.", 409);
        }

        $ticket->update(['statut' => Ticket::STATUT_EN_TRAITEMENT]);

        return $ticket->fresh(self::RELATIONS);
    }

    public function marquerPret(Ticket $ticket): Ticket
    {
        if ($ticket->statut !== Ticket::STATUT_EN_TRAITEMENT) {
            throw new ApiException("Seul un ticket 'en traitement' peut passer 'pret'.", 409);
        }

        $ticket->update(['statut' => Ticket::STATUT_PRET]);

        $ticket = $ticket->fresh(self::RELATIONS);

        $this->notifierPret($ticket);

        return $ticket;
    }

    public function recuperer(Ticket $ticket, ?array $donneesPaiement): Ticket
    {
        if ($ticket->statut !== Ticket::STATUT_PRET) {
            throw new ApiException("Seul un ticket 'pret' peut etre recupere.", 409);
        }

        return DB::transaction(function () use ($ticket, $donneesPaiement) {

            if (! $ticket->estPaye()) {
                if ($donneesPaiement === null) {
                    throw new ApiException(
                        'Le paiement doit etre enregistre pour recuperer ce ticket.',
                        422
                    );
                }

                Paiement::create([
                    'ticket_id' => $ticket->id,
                    'montant' => $donneesPaiement['montant'] ?? $ticket->montant_total,
                    'mode_paiement' => $donneesPaiement['mode_paiement'] ?? Paiement::MODE_ESPECES,
                ]);
            }

            $ticket->update([
                'statut' => Ticket::STATUT_RECUPERE,
                'date_recuperation' => now(),
            ]);

            return $ticket->fresh(self::RELATIONS);
        });
    }


    public function annuler(Ticket $ticket): Ticket
    {
        if (! $ticket->peutEtreAnnule()) {
            throw new ApiException(
                "Seul un ticket 'recu' ou 'en traitement' peut etre annule.",
                409
            );
        }

        $ticket->update([
            'statut' => Ticket::STATUT_ANNULE
        ]);

        Mail::to($ticket->client->email)
            ->send(new CommandeAnnuleeMail($ticket));

        return $ticket->fresh(self::RELATIONS);
    }

    public function getPourUtilisateur(User $utilisateur): Collection
    {
        return Ticket::with(self::RELATIONS)
            ->visiblePar($utilisateur)
            ->latest('date_depot')
            ->get();
    }

    public function getById(int $id, User $utilisateur): Ticket
    {
        return Ticket::with(self::RELATIONS)
            ->visiblePar($utilisateur)
            ->findOrFail($id);
    }

    private function notifierDepot(Ticket $ticket): void
    {
        try {
            Mail::to($ticket->client->email)->send(new TicketConfirmationMail($ticket));

            $gestionnaires = User::where('role', User::ROLE_GESTIONNAIRE)->get();

            if ($gestionnaires->isNotEmpty()) {
                Mail::to($gestionnaires)->send(new NewOrderMail($ticket));
            }
        } catch (\Throwable $e) {
            Log::error('Echec envoi email depot ticket #' . $ticket->id . ' : ' . $e->getMessage());
        }
    }

    private function notifierPret(Ticket $ticket): void
    {
        try {
            Mail::to($ticket->client->email)->send(new TicketReceiptMail($ticket));
        } catch (\Throwable $e) {
            Log::error('Echec envoi email pret ticket #' . $ticket->id . ' : ' . $e->getMessage());
        }
    }
}
