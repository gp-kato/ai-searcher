<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Tag;
use App\Models\Tool;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ToolTagRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_tools_and_tags_have_a_many_to_many_relationship(): void
    {
        $category = Category::create([
            'name' => 'Writing',
            'slug' => 'writing',
        ]);

        $tool = Tool::create([
            'category_id' => $category->id,
            'name' => 'Example Tool',
            'url' => 'https://example.com',
            'description' => 'An example tool.',
            'pricing_system' => 'Free',
        ]);

        $tag = Tag::create([
            'name' => 'Text generation',
            'slug' => 'text-generation',
        ]);

        $tool->tags()->attach($tag);

        $this->assertTrue($tool->tags->contains($tag));
        $this->assertTrue($tag->tools->contains($tool));
    }
}
