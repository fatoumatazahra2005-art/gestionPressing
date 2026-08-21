<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FusionnerPanierRequest;
use App\Http\Requests\PanierItemRequest;
use App\Http\Resources\PanierResource;
use App\Services\PanierService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PanierController extends Controller
{
    public function __construct(
        private readonly PanierService $panierService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $panier = $this->panierService->obtenirPanier($request->user());

        return response()->json([
            'success' => true,
            'data' => new PanierResource($panier),
        ]);
    }

    public function ajouterItem(PanierItemRequest $request): JsonResponse
    {
        $panier = $this->panierService->ajouterItem(
            $request->user(),
            $request->validated('service_id'),
            $request->validated('quantity'),
        );

        return response()->json([
            'success' => true,
            'data' => new PanierResource($panier),
        ]);
    }

    public function modifierQuantite(Request $request, int $serviceId): JsonResponse
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $panier = $this->panierService->modifierQuantite(
            $request->user(),
            $serviceId,
            $request->integer('quantity'),
        );

        return response()->json([
            'success' => true,
            'data' => new PanierResource($panier),
        ]);
    }

    public function supprimerItem(Request $request, int $serviceId): JsonResponse
    {
        $panier = $this->panierService->supprimerItem($request->user(), $serviceId);

        return response()->json([
            'success' => true,
            'data' => new PanierResource($panier),
        ]);
    }

    public function vider(Request $request): JsonResponse
    {
        $panier = $this->panierService->vider($request->user());

        return response()->json([
            'success' => true,
            'data' => new PanierResource($panier),
        ]);
    }

    public function fusionner(FusionnerPanierRequest $request): JsonResponse
    {
        $panier = $this->panierService->fusionner(
            $request->user(),
            $request->validated('items'),
        );

        return response()->json([
            'success' => true,
            'data' => new PanierResource($panier),
        ]);
    }
}
