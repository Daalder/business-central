<?php

namespace BusinessCentral\API\Events\SubscriptionNotice;

use BusinessCentral\Models\SubscriptionNotice;
use Illuminate\Queue\SerializesModels;
use Pionect\Backoffice\Events\Activity\ActivityEvent;
use Pionect\Backoffice\Models\BaseModel;

class SubscriptionNoticeCreated extends ActivityEvent
{
    use SerializesModels;

    /**
     * @var SubscriptionNotice
     */
    public $notice;

    /**
     * SubscriptionNoticeCreated constructor.
     * @param SubscriptionNotice $notice
     */
    public function __construct(SubscriptionNotice $notice)
    {
        parent::__construct();
        $this->notice = $notice;
    }

    /**
     * @inheritDoc
     */
    public function getModel(): BaseModel
    {
        return $this->notice;
    }
}
