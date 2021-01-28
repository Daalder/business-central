<?php

namespace BusinessCentral\API\Listeners\Subscription;

use BusinessCentral\API\Events\Subscription\SubscriptionCreated;
use BusinessCentral\API\HttpClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BusinessCentralSubscriptionRegister implements ShouldQueue
{
    use SerializesModels, Dispatchable, InteractsWithQueue, Queueable;

    /**
     * @var string
     */
    public $queue = 'medium';

    /**
     * @var HttpClient
     */
    private $client;

    /**
     * SubscriptionRegister constructor.
     * @param HttpClient $client
     */
    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param SubscriptionCreated $event
     */
    public function handle(SubscriptionCreated $event)
    {
        if(true !== (bool) $event->subscription->isRegistered) {
            $this->client->subscription()->apiRegister($event->subscription);
        }
    }
}