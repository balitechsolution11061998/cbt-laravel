<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ResponseJson
{
    public static function response(string $message, string $title, array $data, int $errorCode): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'title' => $title,
            'data' => $data,
            'error_code' => $errorCode,
        ], $errorCode);
    }
}
