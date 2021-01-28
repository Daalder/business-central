<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Listeners\SubscriptionNotice;

use Daalder\BusinessCentral\API\Events\SubscriptionNotice\SubscriptionNoticeCreated;
use Daalder\BusinessCentral\API\HttpClient;
use Daalder\BusinessCentral\API\Services\SubscriptionNoticeService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SubscriptionNoticeProcess implements ShouldQueue
{
    use SerializesModels, Dispatchable, Queueable, InteractsWithQueue;

    public string $queue = 'medium';

    private HttpClient $client;

    private SubscriptionNoticeService $service;

    /**
     * SubscriptionRegister constructor.
     */
    public function __construct(HttpClient $client, SubscriptionNoticeService $service)
    {
        $this->client = $client;
        $this->service = $service;
    }

    /**
     * Handle the listener.
     *
     * @throws Exception
     */
    public function handle(SubscriptionNoticeCreated $event): void
    {
        $this->service->process($event->notice);
    }
}
