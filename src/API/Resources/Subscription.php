<?php

namespace BusinessCentral\API\Resources;

use BusinessCentral\Repositories\ReferenceRepository;
use Illuminate\Http\Resources\Json\Resource;

/**
 * Class Subscription
 * @package BusinessCentral\API\Resources
 * @mixin \BusinessCentral\Models\Subscription
 */
class Subscription extends Resource
{

    /**
     * @var \BusinessCentral\Repositories\ReferenceRepository
     */
    private $referenceRepository;

    public function __construct($resource){
        parent::__construct($resource);

    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'subscriptionId'      => $this->subscriptionId,
            'notificationUrl'     => $this->notificationUrl,
            'resource'            => $this->resourceUrl
        ];

        return $data;
    }

}
