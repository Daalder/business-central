<?php

namespace BusinessCentral\API\Events\Subscription;

use BusinessCentral\Models\Subscription;
use Illuminate\Queue\SerializesModels;
use Pionect\Backoffice\Events\Activity\ActivityEvent;
use Pionect\Backoffice\Models\BaseModel;

class SubscriptionCreated extends ActivityEvent
{
    use SerializesModels;

    /**
     * @var Subscription
     */
    public $subscription;

    /**
     * SubscriptionCreated constructor.
     * @param Subscription $subscription
     */
    public function __construct(Subscription $subscription)
    {
        parent::__construct();
        $this->subscription = $subscription;
    }

    /**
     * @inheritDoc
     */
    public function getModel(): BaseModel
    {
        return $this->subscription;
    }
}