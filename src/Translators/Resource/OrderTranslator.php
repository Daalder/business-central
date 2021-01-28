<?php

namespace BusinessCentral\Translators\Resource;

use BusinessCentral\Repositories\OrderRepository;
use BusinessCentral\Translators\Translator;
use Pionect\Backoffice\Models\Order\Repositories\OrderRepository as BackOfficeOrderRepository;

class OrderTranslator extends Translator
{

    /**
     * Make reference repository.
     *
     * @param null $model
     * @return mixed
     */
    public function makeReferenceRepository($model)
    {
        // TODO: Implement makeReferenceRepository() method.
    }

    /**
     * Update destination entity.
     *
     * @param array $data
     * @return mixed
     */
    public function update(array $data = [])
    {
        // TODO: Implement update() method.
    }

    /**
     * Create destination entity.
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data = [])
    {
        // TODO: Implement create() method.
    }

    /**
     * Create destination entity without storing it in the database.
     *
     * @param array $data
     * @return mixed
     */
    public function prepare(array $data = [])
    {
        // TODO: Implement prepare() method.
    }

    /**
     * Delete destination entity.
     *
     * @return bool
     */
    public function delete(): bool
    {
        // TODO: Implement delete() method.
    }

    /**
     * Get Business Central repository class name.
     *
     * @return string
     */
    public function businessCentralRepositoryName(): string
    {
        return OrderRepository::class;
    }

    /**
     * Get BackOffice repository class name.
     *
     * @return string
     */
    public function backOfficeRepositoryName(): string
    {
        return BackOfficeOrderRepository::class;
    }
}