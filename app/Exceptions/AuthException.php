<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AuthException extends \Exception
{
    public function render(): JsonResponse
    {
        return response()->json([
            'error' => [
                'code' => Response::HTTP_UNAUTHORIZED,
                'message' => 'Unauthorized'
            ]
        ]);
    }
}
