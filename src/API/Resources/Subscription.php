<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class Subscription
 *
 * @package BusinessCentral\API\Resources
 *
 * @mixin \BusinessCentral\Models\Subscription
 */
class Subscription extends Resource
{
    private \BusinessCentral\Repositories\ReferenceRepository $referenceRepository;

    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array
     */
    public function toArray(\Illuminate\Http\Request $request): array
    {
        return [
            'subscriptionId' => $this->subscriptionId,
            'notificationUrl' => $this->notificationUrl,
            'resource' => $this->resourceUrl,
        ];
    }
}
