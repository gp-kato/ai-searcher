<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_category(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->post(route('admin.categories.store'), [
                'name' => '文章生成',
                'slug' => 'text-generation',
            ])
            ->assertRedirect(route('admin.categories'))
            ->assertSessionHas('status', 'カテゴリを追加しました。');

        $this->assertDatabaseHas('categories', [
            'name' => '文章生成',
            'slug' => 'text-generation',
        ]);
    }

    public function test_category_registration_rejects_duplicate_and_invalid_values(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        Category::query()->create([
            'name' => '文章生成',
            'slug' => 'text-generation',
        ]);

        $this->actingAs($admin)
            ->from(route('admin.categories'))
            ->post(route('admin.categories.store'), [
                'name' => '文章生成',
                'slug' => 'invalid slug',
            ])
            ->assertRedirect(route('admin.categories'))
            ->assertSessionHasErrors(['name', 'slug']);

        $this->assertDatabaseCount('categories', 1);
    }

    public function test_non_admin_cannot_access_category_management(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get(route('admin.categories'))
            ->assertForbidden();

        $this->actingAs($user)
            ->post(route('admin.categories.store'), [
                'name' => '文章生成',
                'slug' => 'text-generation',
            ])
            ->assertForbidden();

        $this->assertDatabaseCount('categories', 0);
    }
}
