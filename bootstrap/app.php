<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->respond(function (Response $response) {
            if (in_array($response->getStatusCode(), [404,  403, 401])) {
                return Inertia::render('Error', ['status' => $response->getStatusCode()])
                    ->toResponse(request())
                    ->setStatusCode($response->getStatusCode());
            }

            return $response;
        });
        $exceptions->render(function (\Throwable $e, Request $request) {
            if ($request->expectsJson()) {
                
                // Si es un error de validación, Laravel ya lo maneja bien (422)
                // Aquí capturamos errores generales (500, Database, etc.)
                return response()->json([
                    'message' => 'Error interno del servidor',
                    'error' => config('app.debug') ? $e->getMessage() : 'Consulte con soporte.',
                    'type' => get_class($e),
                ], 500);
            }
        });
    })->create();
