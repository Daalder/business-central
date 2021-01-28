<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Services\Subscription\Webhook;

use Daalder\BusinessCentral\API\Repositories\SubscriptionRepository;
use Daalder\BusinessCentral\API\Services\Subscription\WebhookService;
use Daalder\BusinessCentral\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionService extends WebhookService
{
    protected SubscriptionRepository $repository;

    /**
     * SubscriptionService constructor.
     */
    public function __construct(SubscriptionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Check whether request query has a validationToken,
     */
    public function hasValidationToken(Request $request): array|null|string
    {
        return $request->query('validationToken', false);
    }

    /**
     * Confirm creation of subscription.
     */
    public function registerRenewSubscription(Subscription $subscription, Request $request): bool
    {
        if (! $request->has('clientState') && $request->input('clientState') !== $subscription->clientState) {
            return false;
        }

        return $this->repository->updateRegisterRenew($subscription);
    }
}
