<?php

namespace Modules\DataProcessor\app\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Carbon;
use Modules\DataProcessor\app\Models\ProcessHistory;

class NoRecentProcessRule implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $hasProcessInLast30Days = ProcessHistory::query()
            ->where('object_uuid', $value)
            ->whereDate('created_at', '>=', Carbon::now()->subDays(30))
            ->exists();

        if ($hasProcessInLast30Days) {
            $fail("You can't have process at this time");
        }
    }
}
