<?php

namespace BusinessCentral\Jobs\Subscription;

use BusinessCentral\Models\SubscriptionNotice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateSubscriptionNotice implements ShouldQueue
{
    use Dispatchable, SerializesModels, Queueable, InteractsWithQueue;

    /**
     * @var SubscriptionNotice
     */
    protected $notice;

    /**
     * Create a new job instance.
     * @param SubscriptionNotice $notice
     */
    public function __construct(SubscriptionNotice $notice)
    {
        // TODO It's up to you :)
        $this->queue   = 'medium';
        $this->notice = $notice;
    }

    public function handle()
    {
    }

    /**
     * @return array
     */
    public function tags()
    {
        return ['business-central', 'create-subscription-notice', 'subscription-notice', 'subscription-notice-'.$this->notice->id];
    }
}
