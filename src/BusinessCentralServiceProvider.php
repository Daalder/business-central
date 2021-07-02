<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral;

use Daalder\BusinessCentral\API\ApiBusinessCentralServiceProvider;
use Daalder\BusinessCentral\API\HttpClient as BusinessCentralAPI;
use Daalder\BusinessCentral\Commands\BusinessCentralSubscription;
use Daalder\BusinessCentral\Commands\PullFromBusinessCentral;
use Daalder\BusinessCentral\Commands\PullInventory;
use Daalder\BusinessCentral\Commands\PushToBusinessCentral;
use Daalder\BusinessCentral\Providers\EventServiceProvider;
use Daalder\BusinessCentral\Providers\RouteServiceProvider;
use Daalder\BusinessCentral\Providers\SchedulerServiceProvider;
use Exception;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Token\AccessToken;
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
    public function boot(): void
    {
        parent::boot();

        if (! env('BC_COMPANY')) {
            return;
        }

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'business-central');

        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                BusinessCentralSubscription::class,
                PullFromBusinessCentral::class,
                PullInventory::class,
                PushToBusinessCentral::class,
            ]);
        }
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (! env('BC_COMPANY')) {
            return;
        }

        $this->mergeConfigFrom(
            __DIR__ . '/../config/business-central.php', 'business-central');

        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        $this->app->register(SchedulerServiceProvider::class);

        $this->registerBusinessCentralApi();

        $this->bindBusinessCentralAPI();
    }

    /**
     * Register BusinessCentralApi providers
     */
    protected function registerBusinessCentralApi(): void
    {
        $this->app->register(ApiBusinessCentralServiceProvider::class);
    }

    protected function bindBusinessCentralAPI(): void
    {
        $this->app->bind(BusinessCentralAPI::class, function () {
            $provider = new GenericProvider([
                'clientId' => config('business-central.clientId'),    // The client ID assigned to you by the provider
                'clientSecret' => config('business-central.clientSecret'),    // The client password assigned to you by the provider
                'redirectUri' => 'https://backoffice.nubuiten.nl',
                'urlAuthorize' => 'https://login.windows.net/962abadf-3251-42b9-bf80-f5867aedac7a/oauth2/authorize?resource=https://api.businesscentral.dynamics.com',
                'urlAccessToken' => 'https://login.windows.net/962abadf-3251-42b9-bf80-f5867aedac7a/oauth2/token?resource=https://api.businesscentral.dynamics.com',
                'urlResourceOwnerDetails' => 'http://service.example.com/resource',
            ]);

            $existingAccessToken = $this->getBusinessCentralAccessTokenFromValueStore();

            if ($existingAccessToken->hasExpired()) {
                $newAccessToken = $provider->getAccessToken('refresh_token', [
                    'refresh_token' => $existingAccessToken->getRefreshToken(),
                ]);

                $this->setBusinessCentralAccessTokenToValueStore($newAccessToken);
                // Purge old access token and store new access token to your data store.
            }
            try {
                $existingAccessToken = $this->getBusinessCentralAccessTokenFromValueStore();

                //var_dump($existingAccessToken->getToken()); exit;

                $businessCentral = new BusinessCentralAPI(config('business-central.endpoint').'companies('.config('business-central.companyId').')/');
                $businessCentral->setAuth('oauth', [
                    'token' => $existingAccessToken->getToken(),
                ]);

                return $businessCentral;
            } catch (IdentityProviderException $e) {

                // Failed to get the access token
                exit($e->getMessage());
            }
        });
    }

    /**
     * @throws Exception
     */
    protected function getBusinessCentralAccessTokenFromValueStore(): AccessToken
    {
        if (! file_exists(storage_path('app/BusinessCentral.json'))) {
            throw new Exception('app/BusinessCentral.json is missing.');
        }

        $valuestore = Valuestore::make(storage_path('app/BusinessCentral.json'));
        return new AccessToken($valuestore->all());
    }

    protected function setBusinessCentralAccessTokenToValueStore(AccessToken $accessToken): void
    {
        $valuestore = Valuestore::make(storage_path('app/BusinessCentral.json'));
        $valuestore->flush()->put([
            'access_token' => $accessToken->getToken(),
            'expires' => $accessToken->getExpires(),
            'refresh_token' => $accessToken->getRefreshToken(),
        ]);
    }
}
