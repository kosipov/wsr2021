<?php

namespace App\Http\Validators;

use Illuminate\Support\Facades\Validator;

class FlightValidator
{
    private array $messages = [];

    public function validate(array $flightData): bool
    {
        $validator = Validator::make($flightData, [
            'id' => 'required | exists:flights,id',
            'date' => 'required | date_format:Y-m-d',
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
