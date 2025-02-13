<?php

namespace App\Exceptions;

use App\Http\Controllers\Api\V1\Traits\ExceptionResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (\Throwable $e) {
        });
    }

    /**
     * Renderiza exceções personalizadas.
     */
    public function render($request, \Throwable $exception): JsonResponse
    {
        if ($exception instanceof ModelNotFoundException) {
            return ExceptionResponse::errorMessage('Not Found', 404, ['error' => 'Resource not found']);
        }

        return parent::render($request, $exception);
    }
}
