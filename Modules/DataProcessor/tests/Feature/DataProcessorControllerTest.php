<?php

namespace Modules\DataProcessor\tests\Feature;

use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DataProcessorControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function passes_validation_when_no_recent_process_exists()
    {
        $user = User::factory()
            ->hasQuota(1)
            ->create();

        $data = [
            "user_id" => $user->id,
            "object_uuid" => Str::uuid()->toString(),
            "object_volume" => 1
        ];

        $this->post('/api/process-data', $data);

        $this->assertDatabaseHas('process_histories', $data);
        $this->assertDatabaseCount('process_histories', 1);
    }
}
