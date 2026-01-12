<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\Auth\ProfileResource;
use App\Http\Resources\User\StoreResource;
use App\Http\Resources\User\UpdatedResource;
use App\Services\User\StoreService;
use App\Services\User\UpdateService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function data(Request $request): JsonResponse
    {
        $users = User::query()
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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('Dashboard/Users/Index');
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
     */
    public function store(StoreRequest $request, StoreService $storeService){
        $result = $storeService->execute($request->validated());
        return new StoreResource($result);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, UpdateService $updateService, string $id): UpdatedResource
    {
        $user = User::findOrFail($id);

        $result = $updateService->execute($user, $request->validated());

        return new UpdatedResource($result);
    }
    /**
     * Check if DNI exists.
     */
    public function checkDni(Request $request): JsonResponse
    {
        $dni = $request->query('dni');
        $exists = User::where('dni', $dni)->exists();
        return response()->json(['exists' => $exists]);
    }

    /**
     * Get user by DNI.
     */
    public function getByDni($dni)
    {
        $user = User::where('dni', $dni)->with('profile')->first();
        if ($user) {
            return new ProfileResource($user);
        }
        return response()->json(null);
    }

    /**
     * Remove the specified resource from storage.
     */
     public function destroy(string $id): JsonResponse
    {
        $role = User::findOrFail($id);
        $role->delete();

        return response()->json(['message' => 'Registro eliminado exitosamente.']);
    }
}
