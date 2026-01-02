<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Role;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\Roles\StoreRequest;
use App\Http\Requests\Roles\UpdateRequest;
use App\Http\Resources\Roles\StoreResource;
use App\Http\Resources\Roles\UpdateResource;
use App\Services\Role\StoreService;
use App\Services\Role\UpdateService;
use App\Services\Role\DeleteService;

class RolesController extends Controller
{
    public function data(Request $request): JsonResponse
    {
        $roles = Role::query()
        ->when(
            $request->searchText, 
            function($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('created_at','desc')
        ->paginate($request->pageSize ?? 7);
        return response()->json($roles);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('Dashboard/Administrator/Roles/Index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreRequest $request
     * @param StoreService $storeService
     * @return StoreResource
     */
    public function store(StoreRequest $request,StoreService $storeService): StoreResource|JsonResponse
    {
        $result = $storeService->store($request->validated());
        
        return new StoreResource($result);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateRequest $request
     * @param UpdateService $updateService
     * @param string $id
     * @return UpdateResource
     */
    public function update(UpdateRequest $request, UpdateService $updateService, string $id): UpdateResource
    {
        $role = Role::findOrFail($id);
        $result = $updateService->update($role, $request->validated());

        return new UpdateResource($result);
    }

    /**
     * Remove the specified resource from storage.
     * @param DeleteService $deleteService
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DeleteService $deleteService, string $id): JsonResponse
    {
        $role = Role::findOrFail($id);
        $deleteService->delete($role);

        return response()->json(['message' => 'Rol eliminado exitosamente.']);
    }
}
