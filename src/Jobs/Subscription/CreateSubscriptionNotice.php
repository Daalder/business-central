<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Jobs\Subscription;

use Daalder\BusinessCentral\Models\SubscriptionNotice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateSubscriptionNotice implements ShouldQueue
{
    use Dispatchable, SerializesModels, Queueable, InteractsWithQueue;

    protected SubscriptionNotice $notice;

    /**
     * Create a new job instance.
     */
    public function __construct(SubscriptionNotice $notice)
    {
        // TODO It's up to you :)
        $this->queue = 'medium';
        $this->notice = $notice;
    }

    public function handle(): void
    {
    }

    /**
     * @return array
     */
    public function tags(): array
    {
        return ['business-central', 'create-subscription-notice', 'subscription-notice', 'subscription-notice-'.$this->notice->id];
    }
}
