<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Jobs;

use Daalder\BusinessCentral\API\HttpClient;
use Daalder\BusinessCentral\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SubscriptionKeep implements ShouldQueue
{
    use Dispatchable, SerializesModels, Queueable, InteractsWithQueue;

    public $queue = 'high';

    protected Subscription $subscription;

    /**
     * SubscriptionKeep constructor.
     */
    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * Job handler.
     */
    public function handle(): void
    {
        if ((bool) $this->subscription->isRegistered !== true && ! $this->subscription->trashed()) {
            app()->make(HttpClient::class)->subscription()->apiRenew($this->subscription);
        }
    }

    /**
     * @return array
     */
    public function tags(): array
    {
        return ['business-central', 'subscription-keep', 'subscription', 'subscription-'.$this->subscription->id];
    }
}
