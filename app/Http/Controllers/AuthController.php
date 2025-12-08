<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginAuthRequest;
use App\Http\Requests\Auth\StoreAuthRequest;
use App\Http\Resources\Auth\LoginAuthResource;
use App\Http\Resources\Auth\StoreAuthResource;
use App\Services\AuthService;
use App\Services\RegisterService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Handle a login request.
     */
    public function login(LoginAuthRequest $request, AuthService $authService)
    {
        try {
            $result = $authService->login(
                $request->input('identifier'), 
                $request->input('password'),
            );

            $user = $result['user'];
            $token = $result['token'];

            return (new LoginAuthResource($user))->additional([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'status' => 200,
                'message' => 'Inicio de sesión exitoso',
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    /**
     * Register a newly created resource in storage.
     */
    public function register(StoreAuthRequest $request, RegisterService $registerService)
    {
       
        $data = $request->validated();

        $result = $registerService->register($data);

        $user = $result['user'];
        $token = $result['token'];

        return (new StoreAuthResource($user))->additional([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'status' => 200,
            'message' => 'Registro exitoso',
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ], 200);
    }
    


}
