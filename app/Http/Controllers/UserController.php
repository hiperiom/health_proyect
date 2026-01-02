<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    public function data(Request $request): JsonResponse
    {
        $roles = User::query()
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
        return inertia('Dashboard/Administrator/Users/Index');
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
    public function store(StoreUserRequest $request)
    {
        //
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
    public function update(UpdateUserRequest $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
