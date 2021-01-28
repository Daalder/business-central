<?php

declare(strict_types=1);

/**
 * Business Central package configuration.
 */
return [
    'endpoint' => env('BC_ENDPOINT'),
    'companyId' => env('BC_COMPANY'),
    'unitOfMeasureId' => env('BC_UNIT_OF_MEASURE'),
    'host' => env('BC_HOST'),
    'clientId' => env('BC_CLIENT_ID'),
    'clientSecret' => env('BC_CLIENT_SECRET'),
];
