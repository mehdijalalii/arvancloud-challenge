<?php

namespace Modules\DataProcessor\database\seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DataProcessorDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);
        User::factory(10)
            ->hasQuota(1)
            ->hasProcessHistories(5)
            ->create();
    }
}
