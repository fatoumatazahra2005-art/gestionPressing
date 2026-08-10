<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Services\CatalogueService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CatalogueController extends Controller
{
    public function __construct(private CatalogueService $catalogueService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $search = $request->query('search');

        $user = $request->user();

        if ($user && $user->estGestionnaire()) {
            $services = $this->catalogueService->listAll($search);
        } else {
            $services = $this->catalogueService->catalogueClient($search);
        }

        return response()->json(ServiceResource::collection($services));
    }

    public function store(StoreServiceRequest $request): JsonResponse
    {
        $service = $this->catalogueService->create($request->validated());

        return response()->json(new ServiceResource($service), 201);
    }

    public function update(UpdateServiceRequest $request, Service $service): JsonResponse
    {
        $service = $this->catalogueService->update($service, $request->validated());

        return response()->json(new ServiceResource($service));
    }

    public function archive(Request $request, Service $service): JsonResponse
    {
        Gate::authorize('archive', $service);

        $this->catalogueService->archive($service);

        return response()->json(new ServiceResource($service->refresh()));
    }

    public function reactivate(Request $request, Service $service): JsonResponse
    {
        Gate::authorize('archive', $service);

        $this->catalogueService->reactivate($service);

        return response()->json(new ServiceResource($service->refresh()));
    }

    public function destroy(Request $request, Service $service): JsonResponse
    {
        Gate::authorize('delete', $service);

        $this->catalogueService->delete($service);

        return response()->json(['message' => 'Service supprimé.']);
    }
}
