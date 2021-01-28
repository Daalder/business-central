<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Events\Subscription;

use Daalder\BusinessCentral\Models\Subscription;
use Illuminate\Queue\SerializesModels;
use Pionect\Backoffice\Events\Activity\ActivityEvent;
use Pionect\Backoffice\Models\BaseModel;

class SubscriptionCreated extends ActivityEvent
{
    use SerializesModels;

    public Subscription $subscription;

    /**
     * SubscriptionCreated constructor.
     */
    public function __construct(Subscription $subscription)
    {
        parent::__construct();
        $this->subscription = $subscription;
    }

    public function getModel(): BaseModel
    {
        return $this->subscription;
    }
}
