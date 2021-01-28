<?php

namespace BusinessCentral\Repositories;

use BusinessCentral\Models\ReferenceModel;
use Pionect\Backoffice\Models\BaseRepository;

/**
 * Class ReferenceRepository
 *
 * @package BusinessCentral\Repositories
 */
class ReferenceRepository extends BaseRepository
{

    /**
     * @var ReferenceModel
     */
    protected $model;

    /**
     * ReferenceRepository constructor.
     *
     * @param  \BusinessCentral\Models\ReferenceModel  $referenceModel
     */
    public function __construct(ReferenceModel $referenceModel)
    {
        parent::__construct($referenceModel);
    }

    /**
     * @param  \BusinessCentral\Models\ReferenceModel  $model
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function storeReference(ReferenceModel $model)
    {
        $this->model = $model;

        return $this->store([
            $model->getKey()      => $model->{$model->getKey()},
            'business_central_id' => $model->business_central_id
        ]);
    }

    /**
     * @param  \BusinessCentral\Models\ReferenceModel  $model
     * @return mixed
     */
    public function getReference(ReferenceModel $model)
    {
        $this->model = $model;

        return $this->findBy($model->getKey(), '=', $model->{$model->getKey()});
    }

    /**
     * Call getReference and storeReference from statical context.
     *
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        $allowedMethods = [
            'getReference',
            'storeReference',
        ];

        if(in_array($name, $allowedMethods) && is_a($arguments[0], ReferenceModel::class, true)) {
            return (new static($arguments[0]))->{$name}($arguments[0]);
        }

        throw new Exception('Method ' . $name . ' cannot be called statically');
    }
}
