<?php

namespace Modules\DataProcessor\database\factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuotaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\DataProcessor\app\Models\Quota::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'request_rate' => random_int(1,10),
            'volume_rate' => random_int(100000, 200000)
        ];
    }
}

