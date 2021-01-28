<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Services;

use Carbon\Carbon;
use Daalder\BusinessCentral\API\HttpClient;
use Daalder\BusinessCentral\Models\SubscriptionNotice;
use Daalder\BusinessCentral\Translators\TranslatorFactory;
use Exception;
use Illuminate\Support\Facades\DB;

class SubscriptionNoticeService
{
    protected HttpClient $client;

    /**
     * @var array
     */
    protected array $functionNames = [
        'created' => 'create',
        'updated' => 'update',
        'deleted' => 'delete',
    ];

    /**
     * SubscriptionNoticeService constructor.
     */
    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * Process SubscriptionNotice.
     *
     * @throws Exception
     */
    public function process(SubscriptionNotice $notice): bool
    {
        if ($notice->isProcessed) {
            return;
        }

        if ($this->shouldBeProcessed($notice) === false) {
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
     */
    protected function shouldBeProcessed(SubscriptionNotice $notice): bool
    {
        return ($notice->isProcessed !== true) && ($notice->expirationDateTime > Carbon::now());
    }

    /**
     * Convert object to array (for validation purposes).
     *
     * @param mixed $object
     *
     * @return array
     */
    protected function toArray($object): array
    {
        return json_decode(json_encode($object), true);
    }
}
