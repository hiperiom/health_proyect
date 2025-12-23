<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Http\Resources\Auth\LoginResource;
use App\Http\Resources\Auth\RegistrationResource;
use App\Services\Auth\LoginService;
use App\Services\Auth\RegistrationService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

use Inertia\Inertia;

class AuthController extends Controller
{
    /**
     * Handle a login request.
     */
    public function index(LoginRequest $request, LoginService $authService)
    {
        return Inertia::render('Welcome');
    }
    /**
     * Handle a login request.
     */
    /* public function validatecredentials(LoginRequest $request, LoginService $authService)
    {
        dd("hola");
        try {
            $result = $authService->login(
                $request->input('identifier'), 
                $request->input('password'),
            );

            $user = $result['user'];
            $token = $result['token'];

            return (new LoginResource($user))->additional([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'status' => 200,
                'message' => 'Inicio de sesión exitoso',
            ]);
        } catch (\Illuminate\Auth\AuthenticationException $e) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }
    } */

    /**
     * Register a newly created resource in storage.
     */
    /* public function register(RegistrationRequest $request, RegistrationService $registrationService)
    {
        try {
            $data = $request->validated();

            $result = $registrationService->store($data);
            $token = $result['token'];

            return (new RegistrationResource($result))->additional([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'status' => 200,
                'message' => 'Registro exitoso',
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error en el registro'], 500);
        }    
    } */

    /* public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ], 200);
    } */
    


}
