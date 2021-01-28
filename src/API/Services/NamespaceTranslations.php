<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Services;

use Daalder\BusinessCentral\Repositories\ProductRepository;
use Pionect\Backoffice\Models\Order\Repositories\OrderRepository;

class NamespaceTranslations
{
    /**
     * @var array
     */
    public static array $NAMESPACES = [
        'items' => ProductRepository::class,
        'orders' => OrderRepository::class,
    ];
}
