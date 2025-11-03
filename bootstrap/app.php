<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Chúng ta KHÔNG CẦN 3 dòng 'use' của Rate Limiter nữa
// use Illuminate\Cache\RateLimiting\Limit;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\RateLimiter;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', 
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // (Middleware global của bạn)
        $middleware->use([
            \Illuminate\Http\Middleware\HandleCors::class,
            \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
            \Illuminate\Foundation\Http\Middleware\TrimStrings::class,
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        ]);

        // (Group 'api' của bạn)
        $middleware->group('api', [
            // Dòng này đã bị vô hiệu hóa (comment out) để sửa lỗi 500
            // \Illuminate\Routing\Middleware\ThrottleRequests::class.':api', 
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // (Các alias của bạn)
        $middleware->alias([
            'auth.api' => \App\Http\Middleware\ApiTokenAuth::class, 
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        ]);
    })
    // Chúng ta KHÔNG CẦN khối ->withRateLimiting(...) nữa
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();