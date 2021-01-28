<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Repositories;

use Carbon\Carbon;
use Daalder\BusinessCentral\API\Resources\Subscription as BusinessCentralAPISubscription;
use Daalder\BusinessCentral\API\Services\NamespaceTranslations;
use Daalder\BusinessCentral\Models\Subscription;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * // TODO (MK) Unify methods as needed.
 *
 * Class SubscriptionRepository
 *
 * @package BusinessCentral\API\Repositories
 */
class SubscriptionRepository extends RepositoryAbstract
{
    public string $objectName = 'subscription';

    public function create(Subscription $subscription, bool $resolveFromApi = true): void
    {
        if ($resolveFromApi) {
            $subscriptionResource = new BusinessCentralAPISubscription($subscription);

            try {
                $response = $this->client->post(
                    config('business-central.endpoint') . 'subscriptions', $subscriptionResource->resolve()
                );
                $subscription->expirationDateTime = $response->expirationDateTime;
                $subscription->save();
            } catch (Exception $exception) {
                print_r($subscriptionResource->resolve());
                dd($exception->getMessage());
            }
        }
    }

    /**
     * Update Subscription to set it as registered from webhook.
     */
    public function updateRegisterRenew(Subscription $subscription): bool
    {
        $subscriptionResource = new BusinessCentralAPISubscription($subscription);

        try {
            $response = $this->client->post(
                config('business-central.endpoint') . 'subscriptions', $subscriptionResource->resolve()
            );
            $subscription->expirationDateTime = $response['expirationDateTime'];
            $subscription->isRegistered = Carbon::parse($response['expirationDateTime'])->greaterThan(Carbon::now());
            $subscription->save();
        } catch (Exception $exception) {
            print_r($subscriptionResource->resolve());
            dd($exception->getMessage());
            return false;
        }

        return $subscription->isRegistered;
    }

    /**
     * Register Subscription with BusinessCentral 365 API.
     */
    public function apiRegister(Subscription $subscription): void
    {
        $this->apiRegisterRenew($subscription, true);
    }

    /**
     * Renew Subscription with BusinessCentral 365 API.
     */
    public function apiRenew(Subscription $subscription): void
    {
        $this->apiRegisterRenew($subscription, false);
    }

    /**
     * @param array $types
     */
    public function getSubscriptionsToRenew(array $types = []): Collection
    {
        if ($types === []) {
            $types = array_keys(NamespaceTranslations::$NAMESPACES);
        }

        return Subscription::where('isRegistered', true)
            ->where('expirationDateTime', '>', Carbon::now())
            ->whereIn('subscriptionId', $types)
            ->get();
    }

    /**
     * @param array $data
     *
     * @throws Exception
     */
    public function firstOrCreate(array $data = []): Subscription
    {
        if (! isset($data['subscriptionId'])) {
            throw new Exception('No subscription ID provided');
        }

        $subscription = Subscription::where('subscriptionId', $data['subscriptionId'])->first();

        if ($subscription === null) {
            return new Subscription($data);
        }
        return $subscription;

    
    }

    public function delete(Subscription $subscription, bool $updateBusinessCentral = true): bool
    {
        DB::beginTransaction();

        try {
            $subscription->delete();

            if ($updateBusinessCentral) {
                $this->client->delete(
                    config('business-central.endpoint') . 'subscriptions'
                );
            }

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    protected function apiRegisterRenew(Subscription $subscription, bool $register = true): void
    {
        $method = $register ? 'post' : 'patch';

        $resource = new BusinessCentralAPISubscription($subscription);

        try {
            $response = $this->client->{$method}(
                config('business-central.endpoint') . 'subscriptions', $resource->resolve()
            );
        } catch (Exception $exception) {
            print_r($resource->resolve());
            dd($exception->getMessage());
        }
    }
}
