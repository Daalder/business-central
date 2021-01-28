<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class SubscriptionNotice
 *
 * @package BusinessCentral\Models
 *
 * @property   string subscription_id
 * @property   string resource
 * @property   string changeType
 * @property   string lastModifiedDateTime
 * @property   string expirationDateTime
 * @property   bool   isProcessed
 * @property   Subscription $subscription
 */
class SubscriptionNotice extends ReferenceModel
{
    public string $table = 'subscription_notice_business_central';

    /**
     * @var array
     */
    public array $fillable = ['subscription_id', 'expirationDateTime', 'resource', 'changeType', 'lastModifiedDateTime', 'isProcessed'];

    /**
     * @var array
     */
    public array $dates = [
        'expirationDateTime',
        'lastModifiedDateTime',
    ];

    /**
     * @var array
     */
    public array $casts = [
        'isProcessed' => 'boolean',
    ];

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }
}
