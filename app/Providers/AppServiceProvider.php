<?php

namespace App\Providers;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Add this to handle authentication exceptions
        Event::listen(RequestHandled::class, function ($event) {
            $this->handleAuthenticationException($event->request, $event->response);
        });
    }

    /**
     * Handle authentication exceptions
     */
    protected function handleAuthenticationException(Request $request, $response)
    {
        if ($response->exception instanceof AuthenticationException) {
            if ($request->is('api/*')) {
                $response->setContent(json_encode(['message' => 'Unauthenticated.']));
                $response->setStatusCode(401);
            }
        }
    }
}