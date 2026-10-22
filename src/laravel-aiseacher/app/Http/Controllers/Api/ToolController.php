<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'pricing' => ['nullable', 'in:all,free,paid'],
            'sort' => ['nullable', 'in:recommended,newest'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
            'page' => ['nullable', 'integer', 'min:1'],
        ]);

        $tools = Tool::query()
            ->with(['category:id,name,slug', 'tags:id,name,slug', 'functions:id,name,slug'])
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

        if (($filters['sort'] ?? 'recommended') === 'newest') {
            $tools->orderByDesc('created_at')->orderByDesc('id');
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
            'category' => $tool->category,
            'tags' => $tool->tags,
            'functions' => $tool->functions,
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
