<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminEmail = env('ADMIN_EMAIL');
        $adminPassword = env('ADMIN_PASSWORD');
        if (! $adminEmail || ! $adminPassword) {
            throw new RuntimeException('ADMIN_EMAIL and ADMIN_PASSWORD must be set before running DatabaseSeeder.');
        }

        User::query()->updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => env('ADMIN_NAME', 'Administrator'),
                'password' => Hash::make($adminPassword),
                'email_verified_at' => now(),
                'is_admin' => true,
            ],
        );
    }
}
