<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use App\Models\ToolFavorite;
use App\Support\BrowserId;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ToolFavoriteController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $browserId = BrowserId::fromRequest($request);
        $toolIds = ToolFavorite::query()
            ->where('browser_id', $browserId)
            ->orderBy('tool_id')
            ->pluck('tool_id');

        return response()->json(['data' => $toolIds]);
    }

    public function store(Request $request, Tool $tool): JsonResponse
    {
        $browserId = BrowserId::fromRequest($request);

        ToolFavorite::query()->firstOrCreate([
            'browser_id' => $browserId,
            'tool_id' => $tool->id,
        ]);

        return response()->json(['data' => ['tool_id' => $tool->id, 'is_favorite' => true]]);
    }

    public function destroy(Request $request, Tool $tool): JsonResponse
    {
        $browserId = BrowserId::fromRequest($request);

        ToolFavorite::query()
            ->where('browser_id', $browserId)
            ->where('tool_id', $tool->id)
            ->delete();

        return response()->json(['data' => ['tool_id' => $tool->id, 'is_favorite' => false]]);
    }
}
