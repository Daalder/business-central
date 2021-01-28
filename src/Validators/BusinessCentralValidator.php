<?php

namespace BusinessCentral\Validators;

use BusinessCentral\Validators\Contracts\BusinessCentralValidatorContract;
use Illuminate\Support\Facades\Validator;

class BusinessCentralValidator implements BusinessCentralValidatorContract
{
    /**
     * @param array $payload
     * @param array $rules
     * @return Validator
     */
    public static function make(array $payload = [], array $rules = [])
    {
        return (new static())->validate($payload, $rules);
    }

    /**
     * @param array $payload
     * @param array $rules
     * @return Validator
     */
    public function validate(array $payload = [], array $rules = [])
    {
        if($rules === []) {
            $rules = $this->rules();
        }

        return Validator::make($payload, $rules);
    }
}