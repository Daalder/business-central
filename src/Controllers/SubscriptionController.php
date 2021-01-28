<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Controllers;

use Daalder\BusinessCentral\API\Services\Subscription\Webhook\NotificationService;
use Daalder\BusinessCentral\API\Services\Subscription\Webhook\SubscriptionService;
use Daalder\BusinessCentral\Models\Subscription;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Pionect\Backoffice\Http\Controllers\BaseController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;

/**
 * Class SubscriptionController
 *
 * @package BusinessCentral\Controllers
 */
class SubscriptionController extends BaseController
{
    protected NotificationService $webhookNotificationService;

    protected SubscriptionService $webhookSubscriptionService;

    /**
     * {@inheritdoc}
     *
     * SubscriptionController constructor.
     *
     * @param Factory $view
     */
    public function __construct(
        Factory $view,
        SubscriptionService $webhookSubscriptionService,
        NotificationService $webhookNotificationService)
    {
        parent::__construct($view);
        $this->webhookSubscriptionService = $webhookSubscriptionService;
        $this->webhookNotificationService = $webhookNotificationService;
    }

    /**
     * index method for subscription routes.
     * Handshake after the registration of subscription channel
     */
    public function index(Subscription $subscription, Request $request): Response
    {
        // Check for validationToken in the query string
        if (($validationToken = $this->webhookSubscriptionService->hasValidationToken($request)) === false) {
            // A payload notification

            if ($subscription->isRegistered && $this->webhookNotificationService->readPayload($subscription, $request) !== false) {
                return response('', 200)->header('Content-type', 'text/plain');
            }

            throw new BadRequestHttpException();
        }

        if ($this->webhookSubscriptionService->registerRenewSubscription($subscription, $request)) {
            throw new PreconditionFailedHttpException();
        }

        // Force response to return text/plain string
        return response($request->get('validationToken'), 200)->header('Content-type', 'text/plain');
    }
}
