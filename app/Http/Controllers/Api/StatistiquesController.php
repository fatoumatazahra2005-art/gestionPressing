<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Services\StatistiquesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatistiquesController extends Controller
{
    public function __construct(protected StatistiquesService $statistiquesService)
    {
    }

    public function jour(Request $request): JsonResponse
    {
        $this->assertGestionnaire($request);

        return response()->json([
            'success' => true,
            'data' => $this->statistiquesService->statsDuJour(),
        ]);
    }


    public function ticketsParMois(Request $request): JsonResponse
    {
        $this->assertGestionnaire($request);

        $mois = (int) $request->query('mois', 12);

        return response()->json([
            'success' => true,
            'data' => $this->statistiquesService->ticketsParMois($mois),
        ]);
    }

    public function chiffreAffairesParService(Request $request): JsonResponse
    {
        $this->assertGestionnaire($request);

        $mois = (int) $request->query('mois', 6);

        return response()->json([
            'success' => true,
            'data' => $this->statistiquesService->chiffreAffairesParServicePourMois($mois),
        ]);
    }

    private function assertGestionnaire(Request $request): void
    {
        if (! $request->user()->estGestionnaire()) {
            throw new ApiException('Reserve au gestionnaire.', 403);
        }
    }
}
