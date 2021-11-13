<?php

namespace App\Http\Validators;

use Illuminate\Support\Facades\Validator;

class UserLoginValidator
{
    private array $messages = [];

    public function validate(array $requestData): bool
    {
        $validator = Validator::make(
            $requestData,
            [
                'phone' => 'required',
                'password' => 'required'
            ]
        );
        $this->messages = $validator->errors()->messages();
        return !$validator->fails();
    }

    public function getMessages(): array
    {
        return $this->messages;
    }
}
