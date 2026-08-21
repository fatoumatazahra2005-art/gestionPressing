<?php

namespace App\Services;

use App\Models\Panier;
use App\Models\PanierItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PanierService
{
    private const RELATIONS = ['items.service'];

    public function obtenirPanier(User $utilisateur): Panier
    {
        $panier = $this->getOuCreerPanier($utilisateur);

        return $panier->load(self::RELATIONS);
    }

    public function ajouterItem(User $utilisateur, int $serviceId, int $quantity): Panier
    {
        $panier = $this->getOuCreerPanier($utilisateur);

        return DB::transaction(function () use ($panier, $serviceId, $quantity) {

            $item = $panier->items()->where('service_id', $serviceId)->first();

            if ($item) {
                $item->update(['quantity' => $item->quantity + $quantity]);
            } else {
                PanierItem::create([
                    'panier_id' => $panier->id,
                    'service_id' => $serviceId,
                    'quantity' => $quantity,
                ]);
            }

            return $panier->fresh(self::RELATIONS);
        });
    }

    public function modifierQuantite(User $utilisateur, int $serviceId, int $quantity): Panier
    {
        $panier = $this->getOuCreerPanier($utilisateur);

        $item = $panier->items()->where('service_id', $serviceId)->firstOrFail();
        $item->update(['quantity' => $quantity]);

        return $panier->fresh(self::RELATIONS);
    }

    public function supprimerItem(User $utilisateur, int $serviceId): Panier
    {
        $panier = $this->getOuCreerPanier($utilisateur);

        $panier->items()->where('service_id', $serviceId)->delete();

        return $panier->fresh(self::RELATIONS);
    }

    public function vider(User $utilisateur): Panier
    {
        $panier = $this->getOuCreerPanier($utilisateur);

        $panier->items()->delete();

        return $panier->fresh(self::RELATIONS);
    }

    private function getOuCreerPanier(User $utilisateur): Panier
    {
        return Panier::firstOrCreate(['utilisateur_id' => $utilisateur->id]);
    }

    public function fusionner(User $utilisateur, array $itemsLocaux): Panier
    {
        $panier = $this->getOuCreerPanier($utilisateur);

        return DB::transaction(function () use ($panier, $itemsLocaux) {

            foreach ($itemsLocaux as $itemLocal) {
                $item = $panier->items()->where('service_id', $itemLocal['service_id'])->first();

                if ($item) {
                    $item->update(['quantity' => $item->quantity + $itemLocal['quantity']]);
                } else {
                    PanierItem::create([
                        'panier_id' => $panier->id,
                        'service_id' => $itemLocal['service_id'],
                        'quantity' => $itemLocal['quantity'],
                    ]);
                }
            }

            return $panier->fresh(self::RELATIONS);
        });
    }
}
