<?php

namespace BusinessCentral\API\Jobs;

use BusinessCentral\API\HttpClient;
use BusinessCentral\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SubscriptionKeep implements ShouldQueue
{
    use Dispatchable, SerializesModels, Queueable, InteractsWithQueue;

    /**
     * @var Subscription
     */
    protected $subscription;

    /**
     * @var string
     */
    protected $queue = 'high';

    /**
     * SubscriptionKeep constructor.
     * @param Subscription $subscription
     */
    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * Job handler.
     * @return void
     */
    public function handle()
    {
        if (true !== (bool) $this->subscription->isRegistered && !$this->subscription->trashed()) {
            app()->make(HttpClient::class)->subscription()->apiRenew($this->subscription);
        }
    }

    /**
     * @return array
     */
    public function tags()
    {
        return ['business-central', 'subscription-keep', 'subscription', 'subscription-'.$this->subscription->id];
    }
}