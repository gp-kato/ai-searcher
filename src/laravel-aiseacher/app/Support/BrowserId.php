<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

final class BrowserId
{
    public static function fromRequest(Request $request): string
    {
        return Validator::make(
            ['browser_id' => $request->header('X-Browser-Id')],
            ['browser_id' => ['required', 'uuid']],
        )->validate()['browser_id'];
    }
}
