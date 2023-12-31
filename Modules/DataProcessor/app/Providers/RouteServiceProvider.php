<?php

namespace Modules\DataProcessor\app\Providers;

use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The module namespace to assume when generating URLs to actions.
     */
    protected string $moduleNamespace = 'Modules\DataProcessor\app\Http\Controllers';

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     */
    public function boot(): void
    {
        parent::boot();

        $this->rateLimiters();
    }


    /**
     * Define the "Rate Limiters" for the requests.
     */
    private function rateLimiters(): void
    {
        RateLimiter::for('processData', function (Request $request) {
            $user = User::findOrFail($request->user_id);

            if (! $quota = $user->quota) {
                abort(Response::HTTP_FORBIDDEN);
            }

            return Limit::perMinute($quota->request_rate);
        });
    }

    /**
     * Define the routes for the application.
     */
    public function map(): void
    {
        $this->mapApiRoutes();
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->moduleNamespace)
            ->group(module_path('DataProcessor', '/routes/api.php'));
    }
}
