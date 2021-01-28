<?php

namespace BusinessCentral\Translators;

use BusinessCentral\Translators\Contracts\TranslatorContract;
use Exception;

abstract class Translator implements TranslatorContract
{
    /**
     * @var mixed
     */
    protected $translationBase;

    /**
     * @var array
     */
    protected $payload;

    /**
     * @var bool
     */
    protected $isFromBackOffice = false;

    /**
     * @var bool
     */
    protected $isFromBusinessCentral = false;

    /**
     * Translator constructor.
     * @param mixed $translationBase
     * @param array $payload
     */
    public function __construct($translationBase = null, array $payload = [])
    {
        $this->setTranslationBase($translationBase);
        $this->setPayload($payload);
    }

    /**
     * Set translation base. If neccessary, a child class can override method to implement validation, etc.
     *
     * @param $translationBase
     * @return TranslatorContract
     */
    public function setTranslationBase($translationBase): TranslatorContract
    {
        $this->translationBase = $translationBase;
        return $this;
    }

    /**
     * Set payload. If neccessary, a child class can override method to implement validation, etc.
     *
     * @param array $payload
     * @return TranslatorContract
     */
    public function setPayload(array $payload = []): TranslatorContract
    {
        $this->payload = $payload;
        return $this;
    }

    /**
     * @param null $translationBase
     * @return TranslatorContract
     */
    public function fromBackOffice($translationBase = null): TranslatorContract
    {
        $this->isFromBackOffice = true;
        $this->isFromBusinessCentral = false;

        if(null !== $translationBase) {
            return $this->setTranslationBase($translationBase);
        }
    }

    /**
     * @param null $translationBase
     * @return TranslatorContract
     */
    public function fromBusinessCentral($translationBase = null): TranslatorContract
    {
        $this->isFromBackOffice = false;
        $this->isFromBusinessCentral = true;

        if(null !== $translationBase) {
            return $this->setTranslationBase($translationBase);
        }

        return $this;
    }

    /**
     * @param $translationBase
     * @param array $payload
     * @return TranslatorContract
     */
    public function set($translationBase, array $payload = []): TranslatorContract
    {
        $this->setTranslationBase($translationBase);
        $this->setPayload($payload);

        return $this;
    }

    /**
     * @param null $translationBase
     * @param array $payload
     * @return TranslatorContract
     * @throws Exception
     */
    public static function make($translationBase = null, array $payload = []): TranslatorContract
    {
        return TranslatorFactory::make($translationBase, $payload);
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed|void
     * @throws Exception
     */
    public static function __callStatic($name, $arguments)
    {
        switch($name) {
            case 'set':
                $translator = new static($arguments[0], $arguments[1]);
                break;
            default:
                throw new Exception('Method ' . $name . ' cannot be called statically.');
        }
    }

    /**
     * Make origin repository, by previously set translation base.
     *
     * @param null $model
     * @return mixed
     */
    public function makeOriginRepository($model = null)
    {
        if ($this->isFromBusinessCentral) {
            $originRepositoryClassName = $this->businessCentralRepositoryName();
        } else if ($this->isFromBackOffice) {
            $originRepositoryClassName = $this->backOfficeRepositoryName();
        } else {
            return false;
        }

        return new $originRepositoryClassName($model);
    }

    /**
     * Make destination repository, by previously set translation base.
     *
     * @param null $model
     * @return mixed
     */
    public function makeDestinationRepository($model = null)
    {
        if($this->isFromBackOffice) {
            $originRepositoryClassName = $this->businessCentralRepositoryName();
        } else if($this->isFromBusinessCentral) {
            $originRepositoryClassName = $this->backOfficeRepositoryName();
        } else {
            return false;
        }

        return new $originRepositoryClassName($model);
    }

    /**
     * @param string $validatorClass
     * @param array $payload
     * @param bool $debug
     * @return bool
     * @throws Exception
     */
    protected function validatePayload(string $validatorClass, array $payload, bool $debug = false): bool
    {
        if(!class_exists($validatorClass)) {
            throw new Exception('Validator class ' . $validatorClass . ' does not exist');
        }

        $validator = $validatorClass::make($payload);

        if($validator->fails()) {
            if(!$debug) {
                return false;
            }
            report(new Exception($validator->errors(), 422));
        }

        return true;
    }
}