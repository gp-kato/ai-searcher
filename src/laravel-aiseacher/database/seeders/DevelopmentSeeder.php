<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DevelopmentSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            User::factory()->raw([
                'name' => 'Local Administrator',
                'email' => 'admin@example.com',
                'is_admin' => true,
            ]),
        );

        User::query()->updateOrCreate(
            ['email' => 'test@example.com'],
            User::factory()->raw([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'is_admin' => false,
            ]),
        );
    }
}
