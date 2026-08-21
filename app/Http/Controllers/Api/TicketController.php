<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTicketRequest;
use App\Http\Requests\RecupererRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use App\Services\TicketWorkflowService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function __construct(protected TicketWorkflowService $ticketService)
    {
    }


    public function index(Request $request): JsonResponse
    {
        $tickets = $this->ticketService->getPourUtilisateur($request->user());

        return response()->json([
            'success' => true,
            'data' => TicketResource::collection($tickets),
        ]);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $ticket = $this->ticketService->getById($id, $request->user());

        return response()->json([
            'success' => true,
            'data' => new TicketResource($ticket),
        ]);
    }


    public function store(CreateTicketRequest $request): JsonResponse
    {
        $this->assertClient($request);

        $ticket = $this->ticketService->creer(
            $request->validated('lignes'),
            $request->user()
        );

        return response()->json([
            'success' => true,
            'data' => new TicketResource($ticket),
        ], 201);
    }

    public function passerEnTraitement(Request $request, Ticket $ticket): JsonResponse
    {
        $this->assertGestionnaire($request);

        $ticket = $this->ticketService->passerEnTraitement($ticket);

        return response()->json(['success' => true, 'data' => new TicketResource($ticket)]);
    }

    public function marquerPret(Request $request, Ticket $ticket): JsonResponse
    {
        $this->assertGestionnaire($request);

        $ticket = $this->ticketService->marquerPret($ticket);

        return response()->json(['success' => true, 'data' => new TicketResource($ticket)]);
    }


    public function recuperer(RecupererRequest $request, Ticket $ticket): JsonResponse
    {
        $this->assertGestionnaire($request);

        $donneesPaiement = $request->has('montant') || $request->has('mode_paiement')
            ? $request->validated()
            : null;

        $ticket = $this->ticketService->recuperer($ticket, $donneesPaiement);

        return response()->json(['success' => true, 'data' => new TicketResource($ticket)]);
    }

    public function annuler(Request $request, Ticket $ticket): JsonResponse
    {
        $this->assertGestionnaire($request);

        $ticket = $this->ticketService->annuler($ticket);

        return response()->json(['success' => true, 'data' => new TicketResource($ticket)]);
    }

    private function assertGestionnaire(Request $request): void
    {
        if (! $request->user()->estGestionnaire()) {
            throw new ApiException('Reserve au gestionnaire.', 403);
        }
    }

    private function assertClient(Request $request): void
    {
        if (! $request->user()->estClient()) {
            throw new ApiException('Reserve aux clients.', 403);
        }
    }
}
