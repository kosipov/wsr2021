<?php

namespace App\Http\Formatters;

use Illuminate\Http\Response;

class AbstractFormatter
{
    public function formatValidationError(string $message, array $errorsFieldsWithMess): array
    {
        return [
            'error' =>
                [
                    'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                    "message" => $message,
                    "errors" => $errorsFieldsWithMess
                ]
        ];
    }
}
