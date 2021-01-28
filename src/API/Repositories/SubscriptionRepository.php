<?php

namespace BusinessCentral\API\Repositories;

use BusinessCentral\API\Events\Subscription\SubscriptionRegistered;
use BusinessCentral\API\Events\Subscription\SubscriptionRenewed;
use BusinessCentral\API\Listeners\Subscription\BusinessCentralSubscriptionRegister;
use BusinessCentral\API\Resources\Subscription as BusinessCentralAPISubscription;
use BusinessCentral\API\Services\NamespaceTranslations;
use BusinessCentral\Models\Subscription;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * // TODO (MK) Unify methods as needed.
 *
 * Class SubscriptionRepository
 * @package BusinessCentral\API\Repositories
 */
class SubscriptionRepository extends RepositoryAbstract
{
    /**
     * @var string
     */
    public $objectName = 'subscription';

    /**
     * @param Subscription $subscription
     * @param bool $resolveFromApi
     */
    public function create(Subscription $subscription, $resolveFromApi = true)
    {
        if($resolveFromApi) {

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
     *
     * @param Subscription $subscription
     * @return bool
     */
    public function updateRegisterRenew(Subscription $subscription)
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
     * @param Subscription $subscription
     * @param bool $register
     */
    protected function apiRegisterRenew(Subscription $subscription, bool $register = true)
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

    /**
     * Register Subscription with BusinessCentral 365 API.
     * @param Subscription $subscription
     */
    public function apiRegister(Subscription $subscription)
    {
        $this->apiRegisterRenew($subscription, true);
    }

    /**
     * Renew Subscription with BusinessCentral 365 API.
     * @param Subscription $subscription
     */
    public function apiRenew(Subscription $subscription)
    {
        $this->apiRegisterRenew($subscription, false);
    }

    /**
     * @param array $types
     * @return Collection
     */
    public function getSubscriptionsToRenew(array $types = [])
    {
        if($types === []) {
            $types = array_keys(NamespaceTranslations::$NAMESPACES);
        }

        return Subscription::where('isRegistered', true)
            ->where('expirationDateTime', '>', Carbon::now())
            ->whereIn('subscriptionId', $types)
            ->get();
    }

    /**
     * @param array $data
     * @return Subscription
     * @throws Exception
     */
    public function firstOrCreate(array $data = [])
    {
        if(!isset($data['subscriptionId'])) {
            throw new Exception('No subscription ID provided');
        }

        $subscription = Subscription::where('subscriptionId', $data['subscriptionId'])->first();

        if(null === $subscription) {
            return new Subscription($data);
        } else {
            return $subscription;
        }
    }

    /**
     * @param Subscription $subscription
     * @param bool $updateBusinessCentral
     * @return bool
     */
    public function delete(Subscription $subscription, bool $updateBusinessCentral = true)
    {
        DB::beginTransaction();

        try {
            $subscription->delete();

            if($updateBusinessCentral) {
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

}