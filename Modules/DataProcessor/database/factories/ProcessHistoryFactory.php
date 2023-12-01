<?php

namespace Modules\DataProcessor\database\factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProcessHistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\DataProcessor\app\Models\ProcessHistory::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'object_uuid' => fake()->uuid,
            'object_volume' => random_int(1000, 1000000),
        ];
    }
}

