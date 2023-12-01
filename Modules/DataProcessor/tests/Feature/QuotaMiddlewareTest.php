<?php

namespace Modules\DataProcessor\tests\Feature;

use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuotaMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_quota_exceeded()
    {
        $user = User::factory()
            ->hasQuota([
                'volume_rate' => 1024
            ])
            ->hasProcessHistories([
                'object_volume' => 1024
            ])
            ->create();

        $this->post('/api/process-data', [
            "user_id" => $user->id,
            "object_uuid" => Str::uuid()->toString(),
            "object_volume" => 1,
        ])->assertStatus(429);
    }

    public function test_quota_not_exceeded()
    {
        $user = User::factory()
            ->hasQuota([
                'volume_rate' => 1024
            ])
            ->hasProcessHistories([
                'object_volume' => 1023
            ])
            ->create();

        $this->post('/api/process-data', [
            "user_id" => $user->id,
            "object_uuid" => Str::uuid()->toString(),
            "object_volume" => 1,
        ])->assertStatus(200);
    }
}
