<?php

namespace App\Http\Validators;

use Illuminate\Support\Facades\Validator;

class PassengerValidator
{
    private array $messages = [];

    public function validate(array $passengerData): bool
    {
        $validator = Validator::make($passengerData, [
            'first_name' => 'required',
            'last_name' => 'required',
            'birth_date' => 'required | date_format:Y-m-d',
            'document_number' => 'required | digits:10',
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
