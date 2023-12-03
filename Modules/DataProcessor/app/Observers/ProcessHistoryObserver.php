<?php

namespace Modules\DataProcessor\App\Observers;

use Modules\DataProcessor\app\Models\ProcessHistory;
use Modules\DataProcessor\services\QuotaService;

class ProcessHistoryObserver
{
    /**
     * Handle the ProcessHistoryObserver "created" event.
     */
    public function created(ProcessHistory $processHistory)
    {
        QuotaService::incrementVolumeUsage($processHistory->user_id, $processHistory->object_volume);
    }
}
