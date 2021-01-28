<?php

namespace BusinessCentral\API\Services;

use BusinessCentral\API\HttpClient;
use BusinessCentral\Models\SubscriptionNotice;
use BusinessCentral\Translators\Contracts\TranslatorContract;
use BusinessCentral\Translators\TranslatorFactory;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Zendesk\API\Exceptions\ApiResponseException;
use Zendesk\API\Exceptions\AuthException;

class SubscriptionNoticeService
{
    /**
     * @var HttpClient
     */
    protected $client;

    /**
     * @var array
     */
    protected $functionNames = [
        'created' => 'create',
        'updated' => 'update',
        'deleted' => 'delete'
    ];

    /**
     * SubscriptionNoticeService constructor.
     * @param HttpClient $client
     */
    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * Process SubscriptionNotice.
     *
     * @param SubscriptionNotice $notice
     * @return bool
     * @throws Exception
     */
    public function process(SubscriptionNotice $notice)
    {
        if($notice->isProcessed) {
            return;
        }

        if(false === $this->shouldBeProcessed($notice)) {
            $notice->isProcessed = true;
            $notice->save();

            return;
        }

        DB::beginTransaction();

        try {
            $translator = TranslatorFactory::businessCentral($notice->resource);
            $result = $this->toArray($this->client->get($notice->resource));

            if ($notice->changeType === 'deleted') {
                $translator->delete();
            } else {
                $translator->{$this->functionNames[$notice->changeType]}($result);
            }

            $notice->update([
                'isProcessed' => true,
            ]);

            DB::commit();
            return true;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }

    /**
     * Check whether SubscriptionNotice should be processed.
     *
     * @param SubscriptionNotice $notice
     * @return bool
     */
    protected function shouldBeProcessed(SubscriptionNotice $notice)
    {
        return (true !== $notice->isProcessed) && ($notice->expirationDateTime > Carbon::now());
    }

    /**
     * Convert object to array (for validation purposes).
     *
     * @param mixed $object
     * @return array
     */
    protected function toArray($object): array
    {
        return json_decode(json_encode($object), true);
    }
}