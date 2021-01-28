<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Validators\Contracts;

use Illuminate\Support\Facades\Validator;

interface BusinessCentralValidatorContract
{
    /**
     * @param array $payload
     * @param array $rules
     */
    public static function make(array $payload = [], array $rules = []): Validator;

    /**
     * @param array $payload
     * @param array $rules
     */
    public function validate(array $payload = [], array $rules = []): Validator;
}
