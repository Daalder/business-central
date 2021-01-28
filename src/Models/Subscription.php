<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Models;

use Daalder\BusinessCentral\Models\Traits\BootClientState;
use Daalder\BusinessCentral\Models\Traits\BootExpirationDateTime;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Pionect\Backoffice\Models\BaseModel;
/**
 * Class Subscription
 *
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

    public string $table = 'subscription_business_central';

    /**
     * @var array
     */
    public array $fillable = [
        'subscriptionId',
        'notificationUrl',
        'resourceUrl',
        'clientState',
        'lastModifiedDateTime',
        'expirationDateTime',
        'isRegistered',
    ];

    public function notices(): HasMany
    {
        return $this->hasMany(SubscriptionNotice::class);
    }

    public function getRouteKeyName(): string
    {
        return 'subscriptionId';
    }
}
