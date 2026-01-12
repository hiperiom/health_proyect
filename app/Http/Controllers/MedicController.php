<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medic;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\Medic\StoreRequest;
use App\Http\Requests\Medic\UpdateRequest;
use App\Http\Resources\Medic\StoreResource;
use App\Http\Resources\Medic\UpdateResource;
use App\Services\Medic\StoreService;
use App\Services\Medic\UpdateService;

class MedicController extends Controller
{
	public function data(Request $request): JsonResponse
	{
		$users = Medic::query()
        ->with('profile')
        ->when(
            $request->searchText, 
            function($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhereHas('profile', function($qProfile) use ($search) {
                      $qProfile->where('first_names', 'like', "%{$search}%")
                      ->orWhere('last_names', 'like', "%{$search}%");
                  });
            })
            ->orderBy('created_at','desc')
        ->paginate($request->pageSize ?? 7);
        return response()->json($users);
	}

	public function index()
	{
		return inertia('Dashboard/Medic/Index');
	}

	public function create()
	{
		//
	}

	public function store(StoreRequest $request, StoreService $storeService): StoreResource|JsonResponse
	{
		$result = $storeService->execute($request->validated());
		return new StoreResource($result);
	}

	public function show(string $id)
	{
		//
	}

	public function edit(string $id)
	{
		//
	}

	public function update(UpdateRequest $request, UpdateService $updateService, string $id): UpdateResource
	{
		$medic = Medic::findOrFail($id);
		$result = $updateService->execute($medic, $request->validated());
		return new UpdateResource($result);
	}

	public function destroy(string $id): JsonResponse
	{
		$medic = Medic::findOrFail($id);
		$medic->delete();
		return response()->json(['message' => 'Registro eliminado exitosamente.']);
	}
}