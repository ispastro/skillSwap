<?php
namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                 ->prefix('api')
                 ->group(base_path('routes/api.php'));

            Route::middleware('web')
                 ->group(base_path('routes/web.php'));
        });
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            $user = $request->user();

            if ($user) {
                if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
                    $max = 200;
                } elseif (method_exists($user, 'isPremium') && $user->isPremium()) {
                    $max = 100;
                } else {
                    $max = 60;
                }
                return Limit::perMinute($max)->by($user->id);
            }

            return Limit::perMinute(30)->by($request->ip());
        });
    }
}
