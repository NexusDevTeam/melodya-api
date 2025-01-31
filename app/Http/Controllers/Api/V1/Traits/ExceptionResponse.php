<?php

namespace App\Http\Controllers\Api\V1\Traits;

use Illuminate\Http\JsonResponse;

trait ExceptionResponse
{
    public function exceptionMessage($exception): JsonResponse
    {
        return response()->json([
            'errors' =>
                "message: {$exception->getMessage()},
                line: {$exception->getLine()},
                file: {$exception->getFile()}"
            ], 500);
    }

    public function errorMessage(string $message = 'error', int $code = 400, array $errors = []): JsonResponse
    {
        return response()->json([
            'status'  => 'error',
            'message' => $message,
            'errors' => $errors
        ], $code);
    }

    public function responseMessage(string $message = 'success', int $code = 200, array $data = []): JsonResponse
    {
        return response()->json([
            'status'  => "success",
            'message' => $message,
            'data'    => $data,
        ], $code);
    }
}
