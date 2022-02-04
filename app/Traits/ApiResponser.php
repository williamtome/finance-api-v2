<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponser
{
    protected function error(int $code, string $message = null, $data = null): JsonResponse
    {
        return new JsonResponse([
            'status' => 'Error',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function success($data, string $message = null, int $code = 200): JsonResponse
    {
        return new JsonResponse([
            'status' => 'Success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}
