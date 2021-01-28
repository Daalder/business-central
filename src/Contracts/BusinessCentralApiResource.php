<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Contracts;

interface BusinessCentralApiResource
{
    /**
     * Update resource from Business Central.
     *
     * @param array $items
     *
     * @return array
     */
    public function updateFromBusinessCentralApi(array $items = []): array;

    /**
     * Delete resource after Business Central.
     *
     * @param array $items
     *
     * @return array
     */
    public function deleteAfterBusinessCentralApi(array $items = []): array;

    /**
     * Create resource from Business Central.
     *
     * @param array $items
     *
     * @return array
     */
    public function createFromBusinessCentralApi(array $items = []): array;
}
