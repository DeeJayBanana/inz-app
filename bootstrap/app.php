<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // 1. OBSŁUGA OGÓLNEGO BŁĘDU 403
        $exceptions->renderable(function (HttpException $e, Request $request) {

            // Sprawdzamy, czy kod błędu to 403 (Forbidden)
            if ($e->getStatusCode() === Response::HTTP_FORBIDDEN) {

                // Upewniamy się, że nie zwracamy JSON, jeśli to nie jest żądanie AJAX
                if (!$request->expectsJson() && !$request->ajax()) {
                    return redirect()->back()->with('error', 'Odmowa dostępu. Brak uprawnień do tej akcji.');
                }
            }
            // Ważne: Zwracamy null, aby inne błędy HTTP (np. 404) były obsługiwane domyślnie.
            return null;
        });

        // 2. Zostawiamy też dedykowaną obsługę AuthorizationException (dla czystości kodu)
        $exceptions->renderable(function (AuthorizationException $e, Request $request){
            return redirect()->back()->with('error', 'Przepraszamy, nie posiadasz uprawnień');
        });

    })->create();
