<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Validators;

use Carbon\Carbon;
use Daalder\BusinessCentral\API\Services\NamespaceTranslations;
use Daalder\BusinessCentral\Models\Subscription;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SubscriptionNoticeValidator
{
    /**
     * @var array
     */
    protected array $data;

    /**
     * SubscriptionNoticeValidator constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param array $data
     */
    public function validate(): Validator
    {
        return Validator::make($this->data, $this->rules());
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'subscriptionId' => [
                'required',
                Rule::in($this->getSubscriptionIds()),
            ],
            'clientState' => [
                'required',
                'string',
                Rule::in([$this->getClientState()]),
            ],
            'expirationDateTime' => [
                'required',
                'string',
                'date',
            ],
            'resource' => [
                'required',
                'string',
                'regex:'.$this->getResourceRegex(),
            ],
            'changeType' => [
                'required',
                'string',
                Rule::in($this->getChangeTypes()),
            ],
            'lastModifiedDateTime' => [
                'required',
                'string',
                'date',
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getSubscriptionIds(): array
    {
        return array_keys(NamespaceTranslations::$NAMESPACES);
    }

    protected function getClientState(): string
    {
        return Subscription::where('subscriptionId', $this->data['subscriptionId'])
            ->where('expirationDateTime', '>', Carbon::now())
            ->first()
            ->clientState;
    }

    protected function getResourceRegex(): string
    {
        return '/\/([a-z\-]+)\(([a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{12})\)/';
    }

    /**
     * @return array
     */
    protected function getChangeTypes(): array
    {
        return [
            'updated',
            'created',
            'deleted',
        ];
    }
}
