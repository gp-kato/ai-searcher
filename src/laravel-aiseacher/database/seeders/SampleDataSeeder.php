<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tag;
use App\Models\Tool;
use App\Models\ToolFunction;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'sample-writing' => 'サンプル文章生成',
            'sample-images' => 'サンプル画像生成',
            'sample-video-audio' => 'サンプル動画・音声',
            'sample-productivity' => 'サンプル業務効率化',
        ];
        $tags = [
            'sample-writing' => 'サンプル文章作成',
            'sample-summary' => 'サンプル要約',
            'sample-image' => 'サンプル画像生成',
            'sample-design' => 'サンプルデザイン',
            'sample-video' => 'サンプル動画編集',
            'sample-meeting' => 'サンプル議事録',
            'sample-code' => 'サンプルコード',
            'sample-productivity' => 'サンプル業務効率化',
            'sample-analysis' => 'サンプルデータ分析',
        ];
        $functions = [
            'sample-text-generation' => 'サンプルテキスト作成',
            'sample-summarization' => 'サンプル要約',
            'sample-image-generation' => 'サンプル画像生成',
            'sample-image-editing' => 'サンプル画像編集',
            'sample-video-generation' => 'サンプル動画生成',
            'sample-transcription' => 'サンプル文字起こし',
            'sample-code-generation' => 'サンプルコード生成',
            'sample-data-analysis' => 'サンプルデータ分析',
            'sample-translation' => 'サンプル翻訳',
        ];

        $categoryIds = [];
        foreach ($categories as $slug => $name) {
            $categoryIds[$slug] = Category::query()->updateOrCreate(
                ['slug' => $slug],
                ['name' => $name],
            )->id;
        }

        $tagIds = [];
        foreach ($tags as $slug => $name) {
            $tagIds[$slug] = Tag::query()->updateOrCreate(
                ['slug' => $slug],
                ['name' => $name],
            )->id;
        }

        $functionIds = [];
        foreach ($functions as $slug => $name) {
            $functionIds[$slug] = ToolFunction::query()->updateOrCreate(
                ['slug' => $slug],
                ['name' => $name],
            )->id;
        }

        $tools = [
            [
                'slug' => 'writing-assistant',
                'name' => 'サンプル文章アシスタント',
                'category' => 'sample-writing',
                'description' => 'ブログ記事やメールの文章作成を支援するサンプルツールです。',
                'pricing' => 'Free',
                'tags' => ['sample-writing'],
                'functions' => ['sample-text-generation'],
                'days_ago' => 12,
            ],
            [
                'slug' => 'meeting-summary',
                'name' => 'サンプル議事録メーカー',
                'category' => 'sample-writing',
                'description' => '会議メモを読みやすく要約し、議事録の作成を支援します。',
                'pricing' => 'Freemium',
                'tags' => ['sample-summary', 'sample-meeting'],
                'functions' => ['sample-summarization'],
                'days_ago' => 11,
            ],
            [
                'slug' => 'translation-helper',
                'name' => 'サンプル翻訳ヘルパー',
                'category' => 'sample-writing',
                'description' => '短い文章の翻訳や言い換えを試せるサンプルツールです。',
                'pricing' => 'Paid',
                'tags' => ['sample-writing'],
                'functions' => ['sample-translation'],
                'days_ago' => 10,
            ],
            [
                'slug' => 'image-prompt-studio',
                'name' => 'サンプル画像プロンプト工房',
                'category' => 'sample-images',
                'description' => 'テキストからイラストやデザイン案を作るサンプルツールです。',
                'pricing' => 'Freemium',
                'tags' => ['sample-image', 'sample-design'],
                'functions' => ['sample-image-generation'],
                'days_ago' => 9,
            ],
            [
                'slug' => 'background-editor',
                'name' => 'サンプル画像編集ツール',
                'category' => 'sample-images',
                'description' => '画像の背景を整え、簡単な編集を試せるサンプルツールです。',
                'pricing' => 'Free',
                'tags' => ['sample-image'],
                'functions' => ['sample-image-editing'],
                'days_ago' => 8,
            ],
            [
                'slug' => 'illustration-maker',
                'name' => 'サンプルイラストメーカー',
                'category' => 'sample-images',
                'description' => '用途に合わせたイラスト案を作成するサンプルツールです。',
                'pricing' => 'Paid',
                'tags' => ['sample-design'],
                'functions' => ['sample-image-generation'],
                'days_ago' => 7,
            ],
            [
                'slug' => 'video-clip-maker',
                'name' => 'サンプル動画クリップメーカー',
                'category' => 'sample-video-audio',
                'description' => '長い動画から短いクリップを作るサンプルツールです。',
                'pricing' => 'Freemium',
                'tags' => ['sample-video'],
                'functions' => ['sample-video-generation'],
                'days_ago' => 6,
            ],
            [
                'slug' => 'voice-transcriber',
                'name' => 'サンプル音声文字起こし',
                'category' => 'sample-video-audio',
                'description' => '音声をテキストに変換して議事録作成に活用できます。',
                'pricing' => 'Free',
                'tags' => ['sample-meeting'],
                'functions' => ['sample-transcription', 'sample-summarization'],
                'days_ago' => 5,
            ],
            [
                'slug' => 'video-captioner',
                'name' => 'サンプル動画字幕メーカー',
                'category' => 'sample-video-audio',
                'description' => '動画の字幕や短い説明文を作成するサンプルツールです。',
                'pricing' => 'Paid',
                'tags' => ['sample-video', 'sample-writing'],
                'functions' => ['sample-transcription'],
                'days_ago' => 4,
            ],
            [
                'slug' => 'code-helper',
                'name' => 'サンプルコードヘルパー',
                'category' => 'sample-productivity',
                'description' => 'コードのひな形作成や簡単な説明を試せるサンプルツールです。',
                'pricing' => 'Free',
                'tags' => ['sample-code'],
                'functions' => ['sample-code-generation'],
                'days_ago' => 3,
            ],
            [
                'slug' => 'task-organizer',
                'name' => 'サンプルタスク整理アシスタント',
                'category' => 'sample-productivity',
                'description' => 'メモやタスクを整理し、作業の優先順位付けを支援します。',
                'pricing' => 'Freemium',
                'tags' => ['sample-productivity', 'sample-summary'],
                'functions' => ['sample-summarization'],
                'days_ago' => 2,
            ],
            [
                'slug' => 'data-insights',
                'name' => 'サンプルデータ分析アシスタント',
                'category' => 'sample-productivity',
                'description' => '表データの傾向を見つけるサンプル分析ツールです。',
                'pricing' => 'Paid',
                'tags' => ['sample-analysis'],
                'functions' => ['sample-data-analysis'],
                'days_ago' => 1,
            ],
        ];

        $now = now();
        foreach ($tools as $sample) {
            $createdAt = $now->copy()->subDays($sample['days_ago']);
            $tool = Tool::query()->updateOrCreate(
                ['url' => "https://example.com/ai-searcher/sample/{$sample['slug']}"],
                [
                    'category_id' => $categoryIds[$sample['category']],
                    'name' => $sample['name'],
                    'description' => $sample['description'],
                    'pricing_system' => $sample['pricing'],
                    'thumbnail' => null,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ],
            );

            $tool->tags()->sync(array_map(fn (string $slug): int => $tagIds[$slug], $sample['tags']));
            $tool->functions()->sync(array_map(fn (string $slug): int => $functionIds[$slug], $sample['functions']));
        }
    }
}
