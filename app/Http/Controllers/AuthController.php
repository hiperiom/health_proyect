<?php

namespace App\Http\Controllers;

use App\Http\Resources\Auth\ProfileResource;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class AuthController extends Controller
{
    /**
     * Handle a login request.
     */
    public function index()
    {
        return Inertia::render('Welcome',[
            'canLogin'=> Route::has('login'),
            'canRegister'=> Route::has('register'),
            'laravelVersions'=> Application::VERSION,
            'phpVersions'=> PHP_VERSION,
        ]);
    }
    public function show(int $id): ProfileResource
    {
        $result = User::where('id',$id)
        ->with('profile')
        ->first();
        return new ProfileResource($result);
    }

}
