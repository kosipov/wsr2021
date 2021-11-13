<?php

namespace App\Http\Validators;

use Illuminate\Support\Facades\Validator;

class UserRegisterValidator
{
/*    private array $messages = [];

    public function validate1(array $requestData): bool
    {
        $validator = Validator::make(
            $requestData,
            [
                'first_name' => 'required',
                'last_name' => 'required',
                'phone' => 'required | unique:users,phone',
                'document_number' => 'required | digits:10',
                'password' => 'required'
            ]
        );
        $this->messages = $validator->errors()->messages();
        return !$validator->fails();
    }

    public function getMessages(): array
    {
        return $this->messages;
    }*/

    private array $messages = [];

    public function validate(array $validateDate): bool
    {
        $validator = Validator::make($validateDate, [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required | unique:users,phone',
            'document_number' => 'required | digits:10',
            'password' => 'required'
        ]);

        $this->messages = $validator->errors()->messages();

        return !$validator->fails();
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }
}
