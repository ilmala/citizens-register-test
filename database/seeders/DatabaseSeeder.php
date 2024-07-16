<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Family;
use App\Models\Person;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Family::factory()
            ->count(6)
            ->create();

        Person::factory()
            ->count(30)
            ->create();
    }
}
