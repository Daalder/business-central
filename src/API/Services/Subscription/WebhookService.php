<?php

namespace BusinessCentral\API\Services\Subscription;

use BusinessCentral\Models\Subscription;
use JsonSchema\Validator;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class WebhookService
{
    /**
     * Validate JSON against JSON Schema
     *
     * @param object $json
     * @param string|null $schema
     * @return bool
     */
    protected function validateJson(object $json, string $schema = null): bool
    {

        /*
         * If no $schema was provided, we asusme it's a schema for notification
         */
        if(null === $schema) {
            $schema = config('webhooks.notification_schema');
        }

        $validator = new Validator();
        $validator->validate($json, (object) [
            '$ref' => 'file://' . $schema
        ]);

        if(!$validator->isValid()) {
            throw new BadRequestHttpException($validator->getErrors()[0]);
        }

        return true;
    }
}