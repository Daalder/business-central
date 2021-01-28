<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Validators;

use Daalder\BusinessCentral\Validators\Contracts\BusinessCentralValidatorContract;
use Illuminate\Support\Facades\Validator;

class BusinessCentralValidator implements BusinessCentralValidatorContract
{
    /**
     * @param array $payload
     * @param array $rules
     */
    public static function make(array $payload = [], array $rules = []): Validator
    {
        return (new static())->validate($payload, $rules);
    }

    /**
     * @param array $payload
     * @param array $rules
     */
    public function validate(array $payload = [], array $rules = []): Validator
    {
        if ($rules === []) {
            $rules = $this->rules();
        }

        return Validator::make($payload, $rules);
    }
}
