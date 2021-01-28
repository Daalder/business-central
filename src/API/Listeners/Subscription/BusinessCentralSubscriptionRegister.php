<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Listeners\Subscription;

use Daalder\BusinessCentral\API\Events\Subscription\SubscriptionCreated;
use Daalder\BusinessCentral\API\HttpClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BusinessCentralSubscriptionRegister implements ShouldQueue
{
    use SerializesModels, Dispatchable, InteractsWithQueue, Queueable;

    public string $queue = 'medium';

    private HttpClient $client;

    /**
     * SubscriptionRegister constructor.
     */
    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    public function handle(SubscriptionCreated $event): void
    {
        if ((bool) $event->subscription->isRegistered !== true) {
            $this->client->subscription()->apiRegister($event->subscription);
        }
    }
}
