<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\UserOwnership;

// Create the app instance first
$app = Application::configure(basePath: dirname(__DIR__));

// Register route middleware BEFORE loading web routes
// app('router') gets the Router instance from Laravel’s service container.
app('router')->aliasMiddleware('user.owns.project', UserOwnership::class);

// Load routes
$app->withRouting(
    web: __DIR__.'/../routes/web.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
)
->withMiddleware(function (Middleware $middleware): void {
    // global middleware if needed
})
->withExceptions(function (Exceptions $exceptions): void {
    //
});

return $app->create();

// Route middleware → applied per route, runs after model binding, safe for ownership checks.
// Global middleware → runs on every request, before model binding, not safe for checks that need model data from route parameters.
