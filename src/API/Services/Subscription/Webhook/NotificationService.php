<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Services\Subscription\Webhook;

use Daalder\BusinessCentral\API\Repositories\SubscriptionNoticeRepository;
use Daalder\BusinessCentral\API\Services\Subscription\WebhookService;
use Daalder\BusinessCentral\API\Validators\SubscriptionNoticeValidator;
use Daalder\BusinessCentral\Models\Subscription;
use Daalder\BusinessCentral\Models\SubscriptionNotice;
use Exception;
use Illuminate\Http\Request;

class NotificationService extends WebhookService
{
    protected SubscriptionNoticeRepository $repository;

    /**
     * NotificationService constructor.
     */
    public function __construct(SubscriptionNoticeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function readPayload(Subscription $subscription, Request $request): bool
    {
        $payload = $request->input('value');

        foreach ($payload as $notificationPayload) {
            $validator = (new SubscriptionNoticeValidator($notificationPayload))->validate();
            if ($validator->fails()) {
                report(new Exception($validator->errors()));
                continue;
            }

            $this->repository->create(new SubscriptionNotice([
                'subscription_id' => $subscription->id,
                'resourceUrl' => $notificationPayload['resourceUrl'],
                'changeType' => $notificationPayload['changeType'],
                'lastModifiedDateTime' => $notificationPayload['lastModifiedDateTime'],
            ]));
        }

        return true;
    }
}
