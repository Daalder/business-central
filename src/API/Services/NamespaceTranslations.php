<?php

namespace BusinessCentral\API\Services;

use BusinessCentral\Repositories\ProductRepository;
use Pionect\Backoffice\Models\Order\Repositories\OrderRepository;

class NamespaceTranslations
{
    /**
     * @var array
     */
    public static $NAMESPACES = [
        'items' => ProductRepository::class,
        'orders' => OrderRepository::class
    ];
}