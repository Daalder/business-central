<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Services\Subscription;

use JsonSchema\Validator;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class WebhookService
{
    /**
     * Validate JSON against JSON Schema
     */
    protected function validateJson(object $json, ?string $schema = null): bool
    {

        /*
         * If no $schema was provided, we asusme it's a schema for notification
         */
        if ($schema === null) {
            $schema = config('webhooks.notification_schema');
        }

        $validator = new Validator();
        $validator->validate($json, (object) [
            '$ref' => 'file://' . $schema,
        ]);

        if (! $validator->isValid()) {
            throw new BadRequestHttpException($validator->getErrors()[0]);
        }

        return true;
    }
}
