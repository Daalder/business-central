<?php


namespace BusinessCentral\Controllers;

use BusinessCentral\Models\Subscription;
use BusinessCentral\API\Services\Subscription\Webhook\NotificationService;
use BusinessCentral\API\Services\Subscription\Webhook\SubscriptionService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Pionect\Backoffice\Http\Controllers\BaseController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;

/**
 * Class SubscriptionController
 * @package BusinessCentral\Controllers
 */
class SubscriptionController extends BaseController
{

    /**
     * @var NotificationService
     */
    protected $webhookNotificationService;

    /**
     * @var SubscriptionService
     */
    protected $webhookSubscriptionService;

    /**
     * @inheritDoc
     *
     * SubscriptionController constructor.
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
     *
     * @param Subscription $subscription
     * @param Request $request
     * @return Response
     */
    public function index(Subscription $subscription, Request $request)
    {
        // Check for validationToken in the query string
        if(false === ($validationToken = $this->webhookSubscriptionService->hasValidationToken($request))) {
            // A payload notification

            if($subscription->isRegistered && false !== $this->webhookNotificationService->readPayload($subscription, $request)) {
                return response('', 200)->header('Content-type', 'text/plain');
            }

            throw new BadRequestHttpException();
        }

        if($this->webhookSubscriptionService->registerRenewSubscription($subscription, $request)) {
            throw new PreconditionFailedHttpException();
        }

        // Force response to return text/plain string
        return response($request->get('validationToken'), 200)->header('Content-type', 'text/plain');
    }

}