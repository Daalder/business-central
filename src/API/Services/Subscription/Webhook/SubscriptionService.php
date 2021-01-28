<?php

namespace BusinessCentral\API\Services\Subscription\Webhook;

use BusinessCentral\API\Repositories\SubscriptionRepository;
use BusinessCentral\Models\Subscription;
use BusinessCentral\API\Services\Subscription\WebhookService;
use Illuminate\Http\Request;

class SubscriptionService extends WebhookService
{
    /**
     * @var SubscriptionRepository
     */
    protected $repository;

    /**
     * SubscriptionService constructor.
     * @param SubscriptionRepository $repository
     */
    public function __construct(SubscriptionRepository $repository)
    {
        $this->repository = $repository;

    }

    /**
     * Check whether request query has a validationToken,
     *
     * @param Request $request
     * @return bool
     */
    public function hasValidationToken(Request $request): bool
    {
        return $request->query('validationToken', false);
    }

    /**
     * Confirm creation of subscription.
     *
     * @param Subscription $subscription
     * @param Request $request
     * @return bool
     */
    public function registerRenewSubscription(Subscription $subscription, Request $request): bool
    {
        if(!$request->has('clientState') && $request->input('clientState') !== $subscription->clientState) {
            return false;
        }

        return $this->repository->updateRegisterRenew($subscription);
    }
}