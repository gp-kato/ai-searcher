<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\Tool;
use App\Models\ToolFunction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ToolController extends Controller
{
    public function admin(): View
    {
        return view('admin.tools', [
            'categories' => \App\Models\Category::query()->orderBy('name')->get(['id', 'name', 'slug']),
            'tags' => Tag::query()->orderBy('name')->get(['id', 'name', 'slug']),
            'functions' => ToolFunction::query()->orderBy('name')->get(['id', 'name', 'slug']),
        ]);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'url' => ['required', 'url:http,https', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'pricing_system' => ['required', 'in:Free,Freemium,Paid'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['integer', 'exists:tags,id'],
            'function_ids' => ['nullable', 'array'],
            'function_ids.*' => ['integer', 'exists:functions,id'],
        ]);

        $tool = DB::transaction(function () use ($validated): Tool {
            $tool = Tool::create([
                'name' => $validated['name'],
                'url' => $validated['url'],
                'description' => $validated['description'],
                'pricing_system' => $validated['pricing_system'],
                'category_id' => $validated['category_id'],
                'is_verified' => false,
            ]);
            $tool->tags()->sync($validated['tag_ids'] ?? []);
            $tool->functions()->sync($validated['function_ids'] ?? []);

            return $tool;
        });

        $tool->load(['category:id,name,slug', 'tags:id,name,slug', 'functions:id,name,slug']);

        if (! $request->expectsJson()) {
            return redirect()->route('admin.tools')->with('status', 'ツールを追加しました。');
        }

        return response()->json([
            'data' => [
                'id' => $tool->id,
                'name' => $tool->name,
                'url' => $tool->url,
                'description' => $tool->description,
                'pricing_system' => $tool->pricing_system,
                'thumbnail' => $tool->thumbnail,
                'is_verified' => $tool->is_verified,
                'category' => $tool->category,
                'tags' => $tool->tags,
                'functions' => $tool->functions,
                'average_rating' => null,
                'rating_count' => 0,
            ],
        ], 201);
    }

    public function tags(): JsonResponse
    {
        return response()->json([
            'data' => Tag::query()->orderBy('name')->get(['id', 'name', 'slug']),
        ]);
    }

    public function functions(): JsonResponse
    {
        return response()->json([
            'data' => ToolFunction::query()->orderBy('name')->get(['id', 'name', 'slug']),
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'pricing' => ['nullable', 'in:all,free,paid'],
            'sort' => ['nullable', 'in:naming,newest,popularity'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
            'page' => ['nullable', 'integer', 'min:1'],
        ]);

        $tools = Tool::query()
            ->with(['category:id,name,slug', 'tags:id,name,slug', 'functions:id,name,slug'])
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->withCount('favorites')
            ->when($filters['q'] ?? null, function (Builder $query, string $keyword): void {
                $query->where(function (Builder $query) use ($keyword): void {
                    $query->where('name', 'like', "%{$keyword}%")
                        ->orWhere('description', 'like', "%{$keyword}%")
                        ->orWhereHas('category', fn (Builder $category) => $category->where('name', 'like', "%{$keyword}%"))
                        ->orWhereHas('tags', fn (Builder $tags) => $tags->where('name', 'like', "%{$keyword}%"))
                        ->orWhereHas('functions', fn (Builder $functions) => $functions->where('name', 'like', "%{$keyword}%"));
                });
            })
            ->when($filters['category'] ?? null, function (Builder $query, string $category): void {
                $query->whereHas('category', function (Builder $query) use ($category): void {
                    $query->where('slug', $category)->orWhere('name', $category);
                });
            })
            ->when(($filters['pricing'] ?? 'all') === 'free', fn (Builder $query) => $query->whereIn('pricing_system', ['Free', 'Freemium']))
            ->when(($filters['pricing'] ?? 'all') === 'paid', fn (Builder $query) => $query->where('pricing_system', 'Paid'));

        if (($filters['sort'] ?? 'naming') === 'newest') {
            $tools->orderByDesc('created_at')->orderByDesc('id');
        } elseif (($filters['sort'] ?? 'recommended') === 'popularity') {
            $tools->orderByDesc('favorites_count')->orderBy('name')->orderBy('id');
        } else {
            $tools->orderBy('name')->orderBy('id');
        }

        $tools = $tools->paginate($filters['per_page'] ?? 9);
        $tools->through(fn (Tool $tool): array => [
            'id' => $tool->id,
            'name' => $tool->name,
            'url' => $tool->url,
            'description' => $tool->description,
            'pricing_system' => $tool->pricing_system,
            'thumbnail' => $tool->thumbnail,
            'is_verified' => $tool->is_verified,
            'category' => $tool->category,
            'tags' => $tool->tags,
            'functions' => $tool->functions,
            'average_rating' => $tool->ratings_avg_rating === null ? null : round((float) $tool->ratings_avg_rating, 1),
            'rating_count' => $tool->ratings_count,
        ]);

        return response()->json([
            'data' => $tools->items(),
            'meta' => [
                'current_page' => $tools->currentPage(),
                'per_page' => $tools->perPage(),
                'total' => $tools->total(),
                'last_page' => $tools->lastPage(),
            ],
        ]);
    }
}
