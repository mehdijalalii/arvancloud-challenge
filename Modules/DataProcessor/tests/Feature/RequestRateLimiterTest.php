<?php

namespace Modules\DataProcessor\tests\Feature;

use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RequestRateLimiterTest extends TestCase
{
    use RefreshDatabase;

    public function test_rate_limiter(): void
    {
        $user = User::factory()
            ->hasQuota(['request_rate' => 1])
            ->create();

        $data = [
            "user_id" => $user->id,
            "object_uuid" => Str::uuid()->toString(),
            "object_volume" => 0
        ];

        $this->post('/api/process-data', $data)->assertStatus(200);
        $this->post('/api/process-data', $data)->assertStatus(429);
    }
}
