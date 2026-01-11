<?php

namespace App\Http\Controllers;

use App\Models\MedicEspeciality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\MedicEspeciality\StoreRequest;
use App\Http\Requests\MedicEspeciality\UpdateRequest;
use App\Http\Resources\MedicEspeciality\StoreResource;
use App\Http\Resources\MedicEspeciality\UpdatedResource;
use App\Services\MedicEspeciality\StoreService;
use App\Services\MedicEspeciality\UpdateService;
use App\Events\MedicEspeciality\DeletedEvent;

class MedicEspecialityController extends Controller
{
    public function data(Request $request): JsonResponse
    {
        $items = MedicEspeciality::query()->when($request->searchText, function($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        })->orderBy('created_at', 'desc')->paginate($request->pageSize ?? 7);
        return response()->json($items);
    }

    public function index()
    {
        return inertia('Dashboard/MedicEspeciality/Index');
    }

    public function store(StoreRequest $request, StoreService $service): StoreResource
    {
        $result = $service->execute($request->validated());
        return new StoreResource($result);
    }

    public function update(UpdateRequest $request, UpdateService $service, $id): UpdatedResource
    {
        $modelInstance = MedicEspeciality::findOrFail($id);
        $result = $service->execute($modelInstance, $request->validated());
        return new UpdatedResource($result);
    }

    public function destroy($id): JsonResponse
    {
        $modelInstance = MedicEspeciality::findOrFail($id);
        $modelInstance->delete();

        return response()->json(['message' => 'Eliminado exitosamente']);
    }
}
