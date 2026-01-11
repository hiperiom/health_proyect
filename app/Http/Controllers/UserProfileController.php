<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserProfile;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\UserProfile\StoreRequest;
use App\Http\Requests\UserProfile\UpdateRequest;
use App\Http\Resources\UserProfile\StoreResource;
use App\Http\Resources\UserProfile\UpdateResource;
use App\Services\UserProfile\StoreService;
use App\Services\UserProfile\UpdateService;

class UserProfileController extends Controller
{
	public function data(Request $request): JsonResponse
	{
		$data = UserProfile::query()
			->when($request->searchText, function($query, $search) {
				$query->where('name', 'like', "%$search%");
			})
			->orderBy('created_at', 'desc')
			->paginate($request->pageSize ?? 7);

		return response()->json($data);
	}

	public function index()
	{
		return inertia('Dashboard/Administrator/UserProfile/Index');
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
		$userprofile = UserProfile::findOrFail($id);
		$result = $updateService->execute($userprofile, $request->validated());
		return new UpdateResource($result);
	}

	public function destroy(string $id): JsonResponse
	{
		$userprofile = UserProfile::findOrFail($id);
		$userprofile->delete();
		return response()->json(['message' => 'Registro eliminado exitosamente.']);
	}
}