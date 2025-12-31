<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Role;
use Inertia\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

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
     */
    public function store(Request $request)
    {
        //
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
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
