<?php

namespace Modules\DataProcessor\app\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class QuotaMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = User::findOrFail($request->user_id);;

        $quota = $user->quota;

        $this->abortIfNoQuota($quota);

        $sumOfSize = $this->calculateSumOfSize($user);

        $this->checkQuotaExceeded($sumOfSize, $quota, $request);

        return $next($request);
    }

    private function abortIfNoQuota($quota): void
    {
        abort_if(! $quota, 403);
    }

    private function calculateSumOfSize($user): int
    {
        return $user->processHistories()->sum('object_volume');
    }

    private function checkQuotaExceeded($sumOfSize, $quota, $request): void
    {
        abort_if($sumOfSize + $request->object_volume > $quota->volume_rate, 429, 'Request denied: monthly limit exceeded');
    }
}
