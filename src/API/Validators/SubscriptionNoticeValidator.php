<?php

namespace BusinessCentral\API\Validators;

use BusinessCentral\API\Services\NamespaceTranslations;
use BusinessCentral\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SubscriptionNoticeValidator {

    /**
     * @var array
     */
    protected $data;

    /**
     * SubscriptionNoticeValidator constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param array $data
     * @return Validator
     */
    public function validate()
    {
        return Validator::make($this->data, $this->rules());
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'subscriptionId' => [
                'required',
                Rule::in($this->getSubscriptionIds())
            ],
            'clientState' => [
                'required',
                'string',
                Rule::in([$this->getClientState()])
            ],
            'expirationDateTime' => [
                'required',
                'string',
                'date'
            ],
            'resource' => [
                'required',
                'string',
                'regex:'.$this->getResourceRegex()
            ],
            'changeType' => [
                'required',
                'string',
                Rule::in($this->getChangeTypes())
            ],
            'lastModifiedDateTime' => [
                'required',
                'string',
                'date'
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getSubscriptionIds()
    {
        return array_keys(NamespaceTranslations::$NAMESPACES);
    }

    /**
     * @return string
     */
    protected function getClientState()
    {
        return Subscription::where('subscriptionId', $this->data['subscriptionId'])
            ->where('expirationDateTime', '>', Carbon::now())
            ->first()
            ->clientState;
    }

    /**
     * @return string
     */
    protected function getResourceRegex()
    {
        $regex = '/\/([a-z\-]+)\(([a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{12})\)/';
        return $regex;
    }

    /**
     * @return array
     */
    protected function getChangeTypes()
    {
        return [
            'updated',
            'created',
            'deleted'
        ];
    }

}