<?php

namespace BusinessCentral\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class SubscriptionNotice
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
class SubscriptionNotice extends ReferenceModel {

    /**
     * @var string
     */
    public $table = 'subscription_notice_business_central';

    /**
     * @var array
     */
    public $fillable = ['subscription_id', "expirationDateTime", 'resource', 'changeType', 'lastModifiedDateTime', 'isProcessed'];

    /**
     * @var array
     */
    public $dates = [
        'expirationDateTime',
        'lastModifiedDateTime'
    ];

    /**
     * @var array 
     */
    public $casts = [
        'isProcessed' => 'boolean'
    ];

    /**
     * @return BelongsTo
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }


}