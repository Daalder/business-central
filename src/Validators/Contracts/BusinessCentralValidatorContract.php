<?php

namespace BusinessCentral\Validators\Contracts;

use Illuminate\Support\Facades\Validator;

interface BusinessCentralValidatorContract
{
    /**
     * @param array $payload
     * @param array $rules
     * @return Validator
     */
    public static function make(array $payload = [], array $rules = []);

    /**
     * @param array $payload
     * @param array $rules
     * @return Validator
     */
    public function validate(array $payload = [], array $rules = []);
}