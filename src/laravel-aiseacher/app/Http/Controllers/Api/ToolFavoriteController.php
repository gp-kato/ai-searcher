<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use App\Models\ToolFavorite;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ToolFavoriteController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $browserId = $this->validatedBrowserId($request);
        $toolIds = ToolFavorite::query()
            ->where('browser_id', $browserId)
            ->orderBy('tool_id')
            ->pluck('tool_id');

        return response()->json(['data' => $toolIds]);
    }

    public function store(Request $request, Tool $tool): JsonResponse
    {
        $browserId = $this->validatedBrowserId($request);

        ToolFavorite::query()->firstOrCreate([
            'browser_id' => $browserId,
            'tool_id' => $tool->id,
        ]);

        return response()->json(['data' => ['tool_id' => $tool->id, 'is_favorite' => true]]);
    }

    public function destroy(Request $request, Tool $tool): JsonResponse
    {
        $browserId = $this->validatedBrowserId($request);

        ToolFavorite::query()
            ->where('browser_id', $browserId)
            ->where('tool_id', $tool->id)
            ->delete();

        return response()->json(['data' => ['tool_id' => $tool->id, 'is_favorite' => false]]);
    }

    private function validatedBrowserId(Request $request): string
    {
        return Validator::make(
            ['browser_id' => $request->header('X-Browser-Id')],
            ['browser_id' => ['required', 'uuid']],
        )->validate()['browser_id'];
    }
}
