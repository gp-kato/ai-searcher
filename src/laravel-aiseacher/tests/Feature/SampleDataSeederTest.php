<?php

namespace Tests\Feature;

use App\Models\Tool;
use Database\Seeders\SampleDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class SampleDataSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_sample_data_is_repeatable_and_searchable_across_pages(): void
    {
        Carbon::setTestNow('2026-10-28 12:00:00');
        try {
            $this->seed(SampleDataSeeder::class);
            $createdAtByUrl = Tool::query()
                ->orderBy('url')
                ->get(['url', 'created_at'])
                ->mapWithKeys(fn (Tool $tool): array => [$tool->url => $tool->created_at->toDateTimeString()])
                ->all();

            Carbon::setTestNow('2026-11-15 12:00:00');
            $this->seed(SampleDataSeeder::class);
            $rerunCreatedAtByUrl = Tool::query()
                ->orderBy('url')
                ->get(['url', 'created_at'])
                ->mapWithKeys(fn (Tool $tool): array => [$tool->url => $tool->created_at->toDateTimeString()])
                ->all();
        } finally {
            Carbon::setTestNow();
        }

        $this->assertSame($createdAtByUrl, $rerunCreatedAtByUrl);
        $this->assertGreaterThan(1, count(array_unique(array_values($createdAtByUrl))));

        $this->assertDatabaseCount('categories', 4);
        $this->assertDatabaseCount('tools', 12);
        $this->assertDatabaseHas('tools', ['pricing_system' => 'Free']);
        $this->assertDatabaseHas('tools', ['pricing_system' => 'Freemium']);
        $this->assertDatabaseHas('tools', ['pricing_system' => 'Paid']);
        $this->assertDatabaseCount('tag_tool', 16);
        $this->assertDatabaseCount('tool_function', 13);

        $this->getJson('/api/categories')
            ->assertOk()
            ->assertJsonCount(4, 'data')
            ->assertJsonPath('data.0.tools_count', 3);

        $firstPage = $this->getJson('/api/tools?sort=newest&per_page=9')
            ->assertOk()
            ->assertJsonPath('meta.total', 12)
            ->assertJsonCount(9, 'data')
            ->assertJsonPath('data.0.name', 'サンプルデータ分析アシスタント');

        $secondPage = $this->getJson('/api/tools?sort=newest&per_page=9&page=2')
            ->assertOk()
            ->assertJsonPath('meta.total', 12)
            ->assertJsonCount(3, 'data');

        $firstPageIds = collect($firstPage->json('data'))->pluck('id');
        $secondPageIds = collect($secondPage->json('data'))->pluck('id');
        $this->assertSame([], $firstPageIds->intersect($secondPageIds)->all());

        $this->getJson('/api/tools?q=文章アシスタント')
            ->assertOk()
            ->assertJsonPath('meta.total', 1);
        $this->getJson('/api/tools?q=サンプル文章生成')
            ->assertOk()
            ->assertJsonPath('meta.total', 3);
        $this->getJson('/api/tools?q=サンプル議事録')
            ->assertOk()
            ->assertJsonPath('meta.total', 2);
        $this->getJson('/api/tools?q=サンプル文字起こし')
            ->assertOk()
            ->assertJsonPath('meta.total', 2);

        $freeWritingTools = $this->getJson('/api/tools?category=sample-writing&pricing=free')
            ->assertOk()
            ->assertJsonPath('meta.total', 2)
            ->assertJsonCount(2, 'data');
        $this->assertEqualsCanonicalizing(
            ['Free', 'Freemium'],
            collect($freeWritingTools->json('data'))->pluck('pricing_system')->all(),
        );
    }
}
