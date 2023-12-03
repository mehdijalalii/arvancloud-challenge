<?php

namespace Modules\DataProcessor\services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Modules\DataProcessor\app\Models\Quota;
use Symfony\Component\HttpFoundation\Response;

class QuotaService
{
    /**
     * Handle user quota check.
     *
     * @param User $user
     * @param int $objectVolume
     * @return void
     */
    public static function handleUserQuota(User $user, int $objectVolume): void
    {
        $quota = $user->quota;

        self::abortIfNoQuota($quota);

        self::checkQuotaExceeded($user, $quota, $objectVolume);
    }

    /**
     * Calculate and save monthly quota if not exist in cache.
     *
     * @param User $user
     * @return int
     */
    public static function calculateMonthlyQuota(User $user): int
    {
        $cacheKey = 'monthly_quota:' . $user->id;
        $lastDayOfMonth = now()->endOfMonth();
        $ttl = now()->diffInSeconds($lastDayOfMonth);

        return Cache::remember($cacheKey, $ttl, function () use ($user) {
            return $user->processHistories()->sum('object_volume');
        });
    }

    /**
     * Check if user quota is exceeded return error.
     *
     * @param User $user
     * @param Quota $quota
     * @param int $objectVolume
     * @return void
     */
    private static function checkQuotaExceeded(User $user, Quota $quota, int $objectVolume): void
    {
        $sumOfSize = self::calculateMonthlyQuota($user);

        self::abortIfQuotaExceeded($quota, $sumOfSize, $objectVolume);
    }

    /**
     * abort if quota exceeded
     *
     * @param int $sumOfSize
     * @param int $objectVolume
     * @param Quota $quota
     * @return void
     */
    public static function abortIfQuotaExceeded(Quota $quota, int $sumOfSize, int $objectVolume): void
    {
        abort_if(
            $sumOfSize + $objectVolume > $quota->volume_rate,
            Response::HTTP_TOO_MANY_REQUESTS,
            'Request denied: monthly limit exceeded'
        );
    }

    /**
     * Abort if no quota is set for the user.
     *
     * @param $quota
     * @return void
     */
    private static function abortIfNoQuota($quota): void
    {
        abort_unless($quota, Response::HTTP_FORBIDDEN);
    }

    /**
     * increment volume usage in cache
     *
     * @param int $userId
     * @param int $objectVolume
     * @return void
     */
    public static function incrementVolumeUsage(int $userId, int $objectVolume): void
    {
        Cache::increment('monthly_quota:' . $userId, $objectVolume);
    }
}
