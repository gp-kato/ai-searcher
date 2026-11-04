<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use App\Models\ToolRating;
use App\Support\BrowserId;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ToolRatingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $ratings = ToolRating::query()
            ->where('browser_id', BrowserId::fromRequest($request))
            ->orderBy('tool_id')
            ->get(['tool_id', 'rating']);

        return response()->json(['data' => $ratings]);
    }

    public function store(Request $request, Tool $tool): JsonResponse
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
        ]);
        $rating = (int) $validated['rating'];

        ToolRating::query()->updateOrCreate(
            [
                'browser_id' => BrowserId::fromRequest($request),
                'tool_id' => $tool->id,
            ],
            ['rating' => $rating],
        );

        return response()->json(['data' => $this->ratingSummary($tool, $rating)]);
    }

    public function destroy(Request $request, Tool $tool): JsonResponse
    {
        ToolRating::query()
            ->where('browser_id', BrowserId::fromRequest($request))
            ->where('tool_id', $tool->id)
            ->delete();

        return response()->json(['data' => $this->ratingSummary($tool, null)]);
    }

    private function ratingSummary(Tool $tool, ?int $rating): array
    {
        $count = $tool->ratings()->count();
        $average = $count > 0 ? round((float) $tool->ratings()->avg('rating'), 1) : null;

        return [
            'tool_id' => $tool->id,
            'rating' => $rating,
            'average_rating' => $average,
            'rating_count' => $count,
        ];
    }
}
