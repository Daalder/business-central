<?php

namespace BusinessCentral\API\Listeners\SubscriptionNotice;

use BusinessCentral\API\Events\SubscriptionNotice\SubscriptionNoticeCreated;
use BusinessCentral\API\HttpClient;
use BusinessCentral\API\Services\SubscriptionNoticeService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SubscriptionNoticeProcess implements ShouldQueue
{
    use SerializesModels, Dispatchable, Queueable, InteractsWithQueue;

    /**
     * @var string
     */
    public $queue = 'medium';

    /**
     * @var HttpClient
     */
    private $client;

    /**
     * @var SubscriptionNoticeService
     */
    private $service;

    /**
     * SubscriptionRegister constructor.
     * @param HttpClient $client
     */
    public function __construct(HttpClient $client, SubscriptionNoticeService $service)
    {
        $this->client = $client;
        $this->service = $service;
    }

    /**
     * Handle the listener.
     * @param SubscriptionNoticeCreated $event
     * @throws Exception
     */
    public function handle(SubscriptionNoticeCreated $event)
    {
        $this->service->process($event->notice);
    }

}
