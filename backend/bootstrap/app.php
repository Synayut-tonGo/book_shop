<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        apiPrefix: 'api',
    )
->withMiddleware(function (Middleware $middleware) {

    // Register JWT and Permission middleware aliases
    $middleware->alias([
        'jwt.auth'    => \Tymon\JWTAuth\Http\Middleware\Authenticate::class,
        'jwt.refresh' => \Tymon\JWTAuth\Http\Middleware\RefreshToken::class,
        'permission'  => \App\Http\Middleware\CheckPermission::class,  // ✅ ADD THIS
    ]);

    // Apply middleware to API group
    $middleware->group('api', [
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ]);
})
->withExceptions(function (Exceptions $exceptions) {
    $exceptions->renderable(function (Throwable $e, $request) {
        if ($request->is('api/*')) {

            // ← Add this block for proper 422 validation responses
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors'  => $e->errors(),           // shows which fields failed
                ], 422);
            }

            // Your existing code for other errors
            $status = 500;

            if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface) {
                $status = $e->getStatusCode();
            } elseif (method_exists($e, 'getCode') && $e->getCode() >= 100 && $e->getCode() < 600) {
                $status = $e->getCode();
            }

            return response()->json([
                'message' => $e->getMessage() ?: 'Server error',
                'status'  => $status,
                'error'   => app()->environment('local') ? get_class($e) : null,
            ], $status);
        }
    });
})
    ->create();