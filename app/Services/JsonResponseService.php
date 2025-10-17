<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;

class JsonResponseService
{


    public function success($data = [], string $message = '', int $status = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $status);
    }


    public function error(string $message = '', int $status = 400, $data = []): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => $data,
        ], $status);
    }
}
