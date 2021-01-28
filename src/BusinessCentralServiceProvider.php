<?php

namespace BusinessCentral;

use BusinessCentral\API\ApiBusinessCentralServiceProvider;
use BusinessCentral\API\HttpClient as BusinessCentralAPI;
use BusinessCentral\Commands\BusinessCentralSubscription;
use BusinessCentral\Commands\PullFromBusinessCentral;
use BusinessCentral\Commands\PullInventory;
use BusinessCentral\Commands\PullWarehouseShipment;
use BusinessCentral\Commands\PushNewOrdersToBusinessCentral;
use BusinessCentral\Commands\PushToBusinessCentral;
use BusinessCentral\Observers\SetObserver;
use BusinessCentral\Providers\EventServiceProvider;
use BusinessCentral\Providers\ModelServiceProvider;
use BusinessCentral\Providers\RouteServiceProvider;
use BusinessCentral\Providers\SchedulerServiceProvider;
use Exception;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Collection;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Token\AccessToken;
use Pionect\Backoffice\Hooks\Facades\Hook;
use Pionect\Backoffice\Menus\Item;
use Pionect\Backoffice\Models\ProductAttribute\Set;
use Spatie\Valuestore\Valuestore;

/**
 * Class BusinessCentralServiceProvider
 *
 * @package BusinessCentral
 */
class BusinessCentralServiceProvider extends ServiceProvider
{
    /**
     * Boot BusinessCentralServiceProvider
     */
    public function boot()
    {
        parent::boot();

        if (!env('BC_COMPANY')) {
            return;
        }


        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'business-central');

        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                BusinessCentralSubscription::class,
                PullFromBusinessCentral::class,
                PullInventory::class,
                PullWarehouseShipment::class,
                PushToBusinessCentral::class,
                PushNewOrdersToBusinessCentral::class
            ]);
        }

        Set::observe(SetObserver::class);

        Hook::listen('main_menu.menu_item.create', function (Collection $items) {
            return $items->push(new Item('/business-central', 'Business Central', [], 'business'));
        });

        Hook::listen('main_menu.business-central.submenu_item.create', function (Collection $items) {
            return $items->push(new Item('/business-central/not-in', 'Niet gekoppelde producten', [], 'settings'));
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (!env('BC_COMPANY')) {
            return;
        }

        $this->mergeConfigFrom(
            __DIR__ . '/../config/business-central.php', 'business-central');

        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        $this->app->register(ModelServiceProvider::class);
        $this->app->register(SchedulerServiceProvider::class);

        $this->registerBusinessCentralApi();

        $this->bindBusinessCentralAPI();
    }

    /**
     * Register BusinessCentralApi providers
     */
    protected function registerBusinessCentralApi()
    {
        $this->app->register(ApiBusinessCentralServiceProvider::class);
    }

    protected function bindBusinessCentralAPI()
    {
        $this->app->bind(BusinessCentralAPI::class, function () {

            $provider = new GenericProvider([
                'clientId' => config('business-central.clientId'),    // The client ID assigned to you by the provider
                'clientSecret' => config('business-central.clientSecret'),    // The client password assigned to you by the provider
                'redirectUri' => 'https://backoffice.nubuiten.nl',
                'urlAuthorize' => 'https://login.windows.net/962abadf-3251-42b9-bf80-f5867aedac7a/oauth2/authorize?resource=https://api.businesscentral.dynamics.com',
                'urlAccessToken' => 'https://login.windows.net/962abadf-3251-42b9-bf80-f5867aedac7a/oauth2/token?resource=https://api.businesscentral.dynamics.com',
                'urlResourceOwnerDetails' => 'http://service.example.com/resource'
            ]);

            $existingAccessToken = $this->getBusinessCentralAccessTokenFromValueStore();


            if ($existingAccessToken->hasExpired()) {

                $newAccessToken = $provider->getAccessToken('refresh_token', [
                    'refresh_token' => $existingAccessToken->getRefreshToken()
                ]);



                $this->setBusinessCentralAccessTokenToValueStore($newAccessToken);
                // Purge old access token and store new access token to your data store.
            }
            try {
                $existingAccessToken = $this->getBusinessCentralAccessTokenFromValueStore();

                //var_dump($existingAccessToken->getToken()); exit;

                $businessCentral = new BusinessCentralAPI(config('business-central.endpoint').'companies('.config('business-central.companyId').')/');
                $businessCentral->setAuth('oauth', [
                    'token' => $existingAccessToken->getToken()
                ]);

                return $businessCentral;


            } catch (IdentityProviderException $e) {

                // Failed to get the access token
                exit($e->getMessage());

            }

        });
    }

    /**
     * @return AccessToken
     * @throws Exception
     */
    protected function getBusinessCentralAccessTokenFromValueStore()
    {
        if (!file_exists(storage_path('app/BusinessCentral.json'))) {
            throw new Exception('app/BusinessCentral.json is missing.');
        }

        $valuestore = Valuestore::make(storage_path('app/BusinessCentral.json'));
        $accessToken = new AccessToken($valuestore->all());

        return $accessToken;
    }

    /**
     * @param AccessToken $accessToken
     */
    protected function setBusinessCentralAccessTokenToValueStore(AccessToken $accessToken)
    {
        $valuestore = Valuestore::make(storage_path('app/BusinessCentral.json'));
        $valuestore->flush()->put([
            'access_token' => $accessToken->getToken(),
            'expires' => $accessToken->getExpires(),
            'refresh_token' => $accessToken->getRefreshToken()
        ]);
    }
}
