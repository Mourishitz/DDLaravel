<?php

use Illuminate\Http\JsonResponse;

if (! function_exists('response_message')) {

    function response_message(?string $message = '', ?int $code = 200): JsonResponse
    {
        return response()->json([
            'message' => __($message),
        ], $code);
    }
}
