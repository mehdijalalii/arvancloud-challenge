<?php

namespace Modules\DataProcessor\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\DataProcessor\app\Http\Requests\ProcessDataRequest;
use Modules\DataProcessor\app\Models\ProcessHistory;

class DataProcessorController extends Controller
{
    public function __invoke(ProcessDataRequest $request)
    {
        $processHistory = ProcessHistory::query()->create($request->validated());

        // queue and storage logics...

        return response()->json([
            'process_id' => $processHistory->id
        ]);
    }
}
