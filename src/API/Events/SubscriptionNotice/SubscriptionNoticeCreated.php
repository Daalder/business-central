<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Events\SubscriptionNotice;

use Daalder\BusinessCentral\Models\SubscriptionNotice;
use Illuminate\Queue\SerializesModels;
use Pionect\Backoffice\Events\Activity\ActivityEvent;
use Pionect\Backoffice\Models\BaseModel;

class SubscriptionNoticeCreated extends ActivityEvent
{
    use SerializesModels;

    public SubscriptionNotice $notice;

    /**
     * SubscriptionNoticeCreated constructor.
     */
    public function __construct(SubscriptionNotice $notice)
    {
        parent::__construct();
        $this->notice = $notice;
    }

    public function getModel(): BaseModel
    {
        return $this->notice;
    }
}
