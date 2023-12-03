<?php

namespace Modules\DataProcessor\tests\Feature;

use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NoRecentProcessRuleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function passes_validation_when_no_recent_process_exists()
    {
        $user = User::factory()
            ->hasQuota(1)
            ->hasProcessHistories([
                'created_at' => now()->startOfYear(),
                'object_volume' => 0

            ])
            ->create();

        $this->post('/api/process-data', [
            "user_id" => $user->id,
            "object_uuid" => Str::uuid()->toString(),
            "object_volume" => 0
        ])->assertStatus(200);
    }

    /** @test */
    public function passes_validation_has_process_exists()
    {
        $user = User::factory()
            ->hasProcessHistories([
                'object_volume' => 0
            ])
            ->create();

        $this->post('/api/process-data', [
            "user_id" => $user->id,
            "object_uuid" => $user->processHistories()->latest()->first()->object_uuid,
            "object_volume" => 0
        ])->assertForbidden();
    }
}
