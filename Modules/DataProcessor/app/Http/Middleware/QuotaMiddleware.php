<?php

namespace Modules\DataProcessor\app\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Modules\DataProcessor\services\QuotaService;

class QuotaMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = User::findOrFail($request->user_id);;

        QuotaService::handleUserQuota($user, $request->object_volume);

        return $next($request);
    }
}
