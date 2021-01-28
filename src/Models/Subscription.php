<?php

namespace BusinessCentral\Models;

use BusinessCentral\Models\Traits\BootClientState;
use BusinessCentral\Models\Traits\BootExpirationDateTime;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Pionect\Backoffice\Models\BaseModel;
/**
 * Class Subscription
 * @package BusinessCentral\Models
 *
 * @property   string subscriptionId
 * @property   string notificationUrl
 * @property   string resourceUrl
 * @property   string clientState
 * @property   string lastModifiedDateTime
 * @property   string expirationDateTime
 * @property   bool   isRegistered
 */
class Subscription extends BaseModel
{
    use BootClientState, BootExpirationDateTime;

    /**
     * @var string
     */
    public $table ='subscription_business_central';

    /**
     * @var array
     */
    public $fillable = [
        'subscriptionId',
        'notificationUrl',
        'resourceUrl',
        'clientState',
        'lastModifiedDateTime',
        'expirationDateTime',
        'isRegistered'
    ];

    /**
     * @return HasMany
     */
    public function notices()
    {
        return $this->hasMany(SubscriptionNotice::class);
    }

    /**
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'subscriptionId';
    }

}