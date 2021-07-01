<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Repositories;

use Daalder\BusinessCentral\Models\ReferenceModel;
use Pionect\Daalder\Models\BaseRepository;

/**
 * Class ReferenceRepository
 *
 * @package BusinessCentral\Repositories
 */
class ReferenceRepository extends BaseRepository
{
    protected $model;

    /**
     * ReferenceRepository constructor.
     *
     * @param ReferenceModel $referenceModel
     */
    public function __construct(ReferenceModel $referenceModel)
    {
        parent::__construct($referenceModel);
    }

    /**
     * Call getReference and storeReference from statical context.
     *
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        $allowedMethods = [
            'getReference',
            'storeReference',
        ];

        if (in_array($name, $allowedMethods) && is_a($arguments[0], ReferenceModel::class, true)) {
            return (new static($arguments[0]))->{$name}($arguments[0]);
        }

        throw new Exception('Method ' . $name . ' cannot be called statically');
    }

    /**
     * @param  ReferenceModel  $model
     */
    public function storeReference(ReferenceModel $model): \Illuminate\Database\Eloquent\Model
    {
        $this->model = $model;

        return $this->store([
            $model->getKey() => $model->{$model->getKey()},
            'business_central_id' => $model->business_central_id,
        ]);
    }

    /**
     * @param  ReferenceModel  $model
     *
     * @return mixed
     */
    public function getReference(ReferenceModel $model)
    {
        $this->model = $model;

        return $this->findBy($model->getKey(), '=', $model->{$model->getKey()});
    }
}
