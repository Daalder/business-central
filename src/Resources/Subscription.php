<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Resources;

use Daalder\BusinessCentral\Repositories\ReferenceRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class Subscription
 *
 * @package BusinessCentral\Resources
 *
 * @mixin Daalder\BusinessCentral\Models\Subscription
 */
class Subscription extends JsonResource
{
    private ReferenceRepository $referenceRepository;

    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    /**
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'subscriptionId' => $this->subscriptionId,
            'notificationUrl' => $this->notificationUrl,
            'resource' => $this->resourceUrl,
        ];
    }
}
