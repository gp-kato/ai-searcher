<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\DevelopmentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use RuntimeException;
use Tests\TestCase;

class UserSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_development_seeder_creates_repeatable_admin_and_non_admin_accounts(): void
    {
        app(DevelopmentSeeder::class)->run();
        app(DevelopmentSeeder::class)->run();

        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseHas('users', [
            'email' => 'admin@example.com',
            'is_admin' => true,
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'is_admin' => false,
        ]);
        $this->assertTrue(Hash::check('password', User::query()->where('email', 'admin@example.com')->firstOrFail()->password));
        $this->assertTrue(Hash::check('password', User::query()->where('email', 'test@example.com')->firstOrFail()->password));
    }

    public function test_database_seeder_creates_only_the_configured_admin_without_factory(): void
    {
        $previous = $this->setAdminEnvironment([
            'ADMIN_EMAIL' => 'admin@example.com',
            'ADMIN_PASSWORD' => 'secure-password',
            'ADMIN_NAME' => 'Production Admin',
        ]);

        try {
            app(DatabaseSeeder::class)->run();
        } finally {
            $this->restoreAdminEnvironment($previous);
        }

        $admin = User::query()->where('email', 'admin@example.com')->firstOrFail();
        $this->assertSame('Production Admin', $admin->name);
        $this->assertTrue($admin->is_admin);
        $this->assertTrue(Hash::check('secure-password', $admin->password));
        $this->assertDatabaseCount('users', 1);
    }

    public function test_database_seeder_requires_admin_credentials(): void
    {
        $previous = $this->setAdminEnvironment([
            'ADMIN_EMAIL' => '',
            'ADMIN_PASSWORD' => '',
            'ADMIN_NAME' => '',
        ]);

        try {
            $this->expectException(RuntimeException::class);
            $this->expectExceptionMessage('ADMIN_EMAIL and ADMIN_PASSWORD must be set');
            app(DatabaseSeeder::class)->run();
        } finally {
            $this->restoreAdminEnvironment($previous);
        }
    }

    /**
     * @param  array<string, string>  $values
     * @return array<string, array{env: string|null, server: string|null, getenv: string|false}>
     */
    private function setAdminEnvironment(array $values): array
    {
        $previous = [];
        foreach ($values as $key => $value) {
            $previous[$key] = [
                'env' => $_ENV[$key] ?? null,
                'server' => $_SERVER[$key] ?? null,
                'getenv' => getenv($key),
            ];
            putenv("{$key}={$value}");
            $_ENV[$key] = $_SERVER[$key] = $value;
        }

        return $previous;
    }

    /**
     * @param  array<string, array{env: string|null, server: string|null, getenv: string|false}>  $values
     */
    private function restoreAdminEnvironment(array $values): void
    {
        foreach ($values as $key => $value) {
            $value['getenv'] === false
                ? putenv($key)
                : putenv("{$key}={$value['getenv']}");

            foreach (['env' => '_ENV', 'server' => '_SERVER'] as $type => $superglobal) {
                if ($value[$type] === null) {
                    unset($GLOBALS[$superglobal][$key]);
                } else {
                    $GLOBALS[$superglobal][$key] = $value[$type];
                }
            }
        }
    }
}
