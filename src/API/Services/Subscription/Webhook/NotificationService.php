<?php

namespace BusinessCentral\API\Services\Subscription\Webhook;

use BusinessCentral\API\Repositories\SubscriptionNoticeRepository;
use BusinessCentral\API\Services\Subscription\WebhookService;
use BusinessCentral\API\Validators\SubscriptionNoticeValidator;
use BusinessCentral\Models\Subscription;
use BusinessCentral\Models\SubscriptionNotice;
use Exception;
use Illuminate\Http\Request;

class NotificationService extends WebhookService
{
    /**
     * @var SubscriptionNoticeRepository
     */
    protected $repository;

    /**
     * NotificationService constructor.
     * @param SubscriptionNoticeRepository $repository
     */
    public function __construct(SubscriptionNoticeRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Subscription $subscription
     * @param Request $request
     * @return bool
     */
    public function readPayload(Subscription $subscription, Request $request)
    {
        $payload = $request->input('value');

        foreach($payload as $notificationPayload) {

            $validator = (new SubscriptionNoticeValidator($notificationPayload))->validate();
            if($validator->fails()) {
                report(new Exception($validator->errors()));
                continue;
            }

            $this->repository->create(new SubscriptionNotice([
                'subscription_id' => $subscription->id,
                'resourceUrl' => $notificationPayload['resourceUrl'],
                'changeType' => $notificationPayload['changeType'],
                'lastModifiedDateTime' => $notificationPayload['lastModifiedDateTime']
            ]));
        }

        return true;
    }
}