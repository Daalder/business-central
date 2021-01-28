<?php

namespace BusinessCentral\Translators\Contracts;

interface TranslatorContract
{
    /**
     * TranslatorContract constructor.
     * @param mixed $translationBase
     * @param array $payload
     */
    public function __construct($translationBase = null, array $payload = []);

    /**
     * Set translation base, f.e. resource URL, model, or properties array.
     *
     * @param $translationBase
     * @return TranslatorContract
     */
    public function setTranslationBase($translationBase): TranslatorContract;

    /**
     * Set payload, f.e. data from controller's request.
     *
     * @param array $payload
     * @return TranslatorContract
     */
    public function setPayload(array $payload = []): TranslatorContract;

    /**
     * Make required translator without TranslatorFactory.
     *
     * @param null $translationBase
     * @param array $payload
     * @return mixed
     */
    public static function make($translationBase = null, array $payload = []): TranslatorContract;

    /**
     * Allow to statically call some of the methods.
     *
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments);

    /**
     * Make origin repository.
     *
     * @param null $model
     * @return mixed
     */
    public function makeOriginRepository($model = null);

    /**
     * Make destination repository,
     *
     * @param null $model
     * @return mixed
     */
    public function makeDestinationRepository($model = null);

    /**
     * Make reference repository.
     *
     * @param null $model
     * @return mixed
     */
    public function makeReferenceRepository($model);

    /**
     * Set translator state as 'fromBackOffice'.
     *
     * @param null $translationBase
     * @return TranslatorContract
     */
    public function fromBackOffice($translationBase = null): TranslatorContract;

    /**
     * Set translator state as 'fromBusinessCentral.
     * @param null $translationBase
     * @return TranslatorContract
     */
    public function fromBusinessCentral($translationBase = null): TranslatorContract;

    /**
     * Update destination entity.
     *
     * @param array $data
     * @return mixed
     */
    public function update(array $data = []);

    /**
     * Create destination entity.
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data = []);

    /**
     * Create destination entity without storing it in the database.
     *
     * @param array $data
     * @return mixed
     */
    public function prepare(array $data = []);

    /**
     * Delete destination entity.
     *
     * @return bool
     */
    public function delete(): bool;

    /**
     * Get Business Central repository class name.
     *
     * @return string
     */
    public function businessCentralRepositoryName(): string;

    /**
     * Get BackOffice repository class name.
     *
     * @return string
     */
    public function backOfficeRepositoryName(): string;
}