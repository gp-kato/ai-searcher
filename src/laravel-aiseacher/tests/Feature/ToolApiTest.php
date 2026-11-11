<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Tag;
use App\Models\Tool;
use App\Models\ToolFunction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ToolApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_tools_api_returns_tools_with_related_category_and_tags(): void
    {
        $category = $this->createCategory('Writing', 'writing');
        $tool = $this->createTool($category, 'Text Helper', 'Freemium');
        $tool->update(['is_verified' => true]);
        $tag = Tag::create(['name' => 'Text generation', 'slug' => 'text-generation']);
        $tool->tags()->attach($tag);

        $this->getJson('/api/tools')
            ->assertOk()
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.name', 'Text Helper')
            ->assertJsonPath('data.0.category.slug', 'writing')
            ->assertJsonPath('data.0.is_verified', true)
            ->assertJsonPath('data.0.average_rating', null)
            ->assertJsonPath('data.0.rating_count', 0)
            ->assertJsonPath('data.0.tags.0.name', 'Text generation');
    }

    public function test_tools_api_filters_by_keyword_category_and_pricing(): void
    {
        $writing = $this->createCategory('Writing', 'writing');
        $images = $this->createCategory('Images', 'images');
        $freeTool = $this->createTool($writing, 'Article Assistant', 'Freemium');
        $paidTool = $this->createTool($writing, 'Paid Writer', 'Paid');
        $this->createTool($images, 'Article Image Maker', 'Free');
        $tag = Tag::create(['name' => 'Long-form writing', 'slug' => 'long-form-writing']);
        $freeTool->tags()->attach($tag);
        $function = ToolFunction::create(['name' => 'Drafting', 'slug' => 'drafting']);
        $freeTool->functions()->attach($function);

        $this->getJson('/api/tools?q=long-form&category=writing&pricing=free')
            ->assertOk()
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.name', 'Article Assistant')
            ->assertJsonPath('data.0.is_verified', false);

        $this->getJson('/api/tools?category=writing&pricing=paid')
            ->assertOk()
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.name', 'Paid Writer');

        $this->getJson('/api/tools?q=drafting')
            ->assertOk()
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.name', 'Article Assistant');
    }

    public function test_categories_api_returns_tool_counts(): void
    {
        $category = $this->createCategory('Writing', 'writing');
        $this->createTool($category, 'Article Assistant');

        $this->getJson('/api/categories')
            ->assertOk()
            ->assertJsonPath('data.0.slug', 'writing')
            ->assertJsonPath('data.0.tools_count', 1);
    }

    public function test_tools_api_validates_filter_parameters(): void
    {
        $this->getJson('/api/tools?pricing=unknown')
            ->assertUnprocessable()
            ->assertJsonValidationErrors('pricing');

        $this->getJson('/api/tools?per_page=1000')
            ->assertUnprocessable()
            ->assertJsonValidationErrors('per_page');
    }

    public function test_favorites_are_saved_and_restored_per_browser(): void
    {
        $tool = $this->createTool($this->createCategory('Writing', 'writing'), 'Text Helper');
        $browserId = 'b44b8c1e-c7b3-458f-a017-09ce7312d55e';
        $otherBrowserId = '7a1dfe05-6884-4e77-94fd-126b0d03daac';

        $this->getJson('/api/favorites', ['X-Browser-Id' => $browserId])
            ->assertOk()
            ->assertJsonPath('data', []);

        $this->putJson("/api/favorites/{$tool->id}", [], ['X-Browser-Id' => $browserId])
            ->assertOk()
            ->assertJsonPath('data.is_favorite', true);

        $this->putJson("/api/favorites/{$tool->id}", [], ['X-Browser-Id' => $browserId])
            ->assertOk();

        $this->getJson('/api/favorites', ['X-Browser-Id' => $browserId])
            ->assertOk()
            ->assertJsonPath('data', [$tool->id]);

        $this->getJson('/api/favorites', ['X-Browser-Id' => $otherBrowserId])
            ->assertOk()
            ->assertJsonPath('data', []);

        $this->deleteJson("/api/favorites/{$tool->id}", [], ['X-Browser-Id' => $browserId])
            ->assertOk()
            ->assertJsonPath('data.is_favorite', false);

        $this->getJson('/api/favorites', ['X-Browser-Id' => $browserId])
            ->assertOk()
            ->assertJsonPath('data', []);
    }

    public function test_favorites_require_a_valid_browser_id(): void
    {
        $this->getJson('/api/favorites')
            ->assertUnprocessable()
            ->assertJsonValidationErrors('browser_id');

        $this->getJson('/api/favorites', ['X-Browser-Id' => 'not-a-uuid'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('browser_id');
    }

    public function test_ratings_are_saved_once_per_browser_and_aggregated(): void
    {
        $tool = $this->createTool($this->createCategory('Writing', 'writing'), 'Text Helper');
        $browserId = 'b44b8c1e-c7b3-458f-a017-09ce7312d55e';
        $otherBrowserId = '7a1dfe05-6884-4e77-94fd-126b0d03daac';

        $this->getJson('/api/ratings', ['X-Browser-Id' => $browserId])
            ->assertOk()
            ->assertJsonPath('data', []);

        $this->putJson("/api/ratings/{$tool->id}", ['rating' => 4], ['X-Browser-Id' => $browserId])
            ->assertOk()
            ->assertJsonPath('data.rating', 4)
            ->assertJsonPath('data.average_rating', 4)
            ->assertJsonPath('data.rating_count', 1);

        $this->putJson("/api/ratings/{$tool->id}", ['rating' => 2], ['X-Browser-Id' => $browserId])
            ->assertOk()
            ->assertJsonPath('data.average_rating', 2)
            ->assertJsonPath('data.rating_count', 1);

        $this->putJson("/api/ratings/{$tool->id}", ['rating' => 4], ['X-Browser-Id' => $otherBrowserId])
            ->assertOk()
            ->assertJsonPath('data.average_rating', 3)
            ->assertJsonPath('data.rating_count', 2);

        $this->getJson('/api/ratings', ['X-Browser-Id' => $browserId])
            ->assertOk()
            ->assertJsonPath('data.0.tool_id', $tool->id)
            ->assertJsonPath('data.0.rating', 2);

        $this->getJson('/api/tools')
            ->assertOk()
            ->assertJsonPath('data.0.average_rating', 3)
            ->assertJsonPath('data.0.rating_count', 2);

        $this->deleteJson("/api/ratings/{$tool->id}", [], ['X-Browser-Id' => $browserId])
            ->assertOk()
            ->assertJsonPath('data.rating', null)
            ->assertJsonPath('data.average_rating', 4)
            ->assertJsonPath('data.rating_count', 1);

        $this->getJson('/api/ratings', ['X-Browser-Id' => $browserId])
            ->assertOk()
            ->assertJsonPath('data', []);
    }

    public function test_ratings_validate_browser_id_and_score(): void
    {
        $tool = $this->createTool($this->createCategory('Writing', 'writing'), 'Text Helper');
        $browserId = 'b44b8c1e-c7b3-458f-a017-09ce7312d55e';

        $this->getJson('/api/ratings')
            ->assertUnprocessable()
            ->assertJsonValidationErrors('browser_id');

        $this->getJson('/api/ratings', ['X-Browser-Id' => 'not-a-uuid'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('browser_id');

        $this->putJson("/api/ratings/{$tool->id}", ['rating' => 0], ['X-Browser-Id' => $browserId])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('rating');

        $this->putJson("/api/ratings/{$tool->id}", ['rating' => 6], ['X-Browser-Id' => $browserId])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('rating');
    }

    private function createCategory(string $name, string $slug): Category
    {
        return Category::create(['name' => $name, 'slug' => $slug]);
    }

    private function createTool(Category $category, string $name, string $pricing = 'Free'): Tool
    {
        return Tool::create([
            'category_id' => $category->id,
            'name' => $name,
            'url' => 'https://example.com',
            'description' => 'An example AI tool.',
            'pricing_system' => $pricing,
        ]);
    }
}
