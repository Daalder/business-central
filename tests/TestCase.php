<?php

namespace Daalder\BusinessCentral\Tests;

use Astrotomic\Translatable\TranslatableServiceProvider;
use Daalder\BusinessCentral\BusinessCentralServiceProvider;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Support\Facades\File;
use Laravel\Passport\PassportServiceProvider;
use Laravel\Scout\ScoutServiceProvider;
use Pionect\Backoffice\DaalderServiceProvider;
use Pionect\Backoffice\ServiceProviders\ElasticScoutConfigServiceProvider;
use Pionect\Backoffice\Tests\TestCase as DaalderTestCase;
use Spatie\Permission\PermissionServiceProvider;

class TestCase extends DaalderTestCase
{

    protected function refreshTestDatabase()
    {
        $locale = app()->getLocale();

        if (!RefreshDatabaseState::$migrated) {
            $this->artisan('vendor:publish', [
                '--provider' => PermissionServiceProvider::class
            ]);

            $this->artisan('migrate:fresh', [
                '--drop-views' => $this->shouldDropViews(),
                '--drop-types' => $this->shouldDropTypes(),
            ]);
            $this->artisan('db:seed');

            $this->app[Kernel::class]->setArtisan(null);

            RefreshDatabaseState::$migrated = true;
        }

        // The locale is modified in the artisan(migrate:fresh) command. Change it back.
        app()->setLocale($locale);

        $this->beginDatabaseTransaction();
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        foreach (File::files('vendor/pionect/daalder/config') as $config) {
            if ($config->getExtension() == 'php') {
                $key = str_replace('.php', '', $config->getFilename());
                $default = config()->get($key, []);
                config()->set($key, array_merge($default, require $config->getRealPath()));
            }
        }

        $orchestra = __DIR__ . '/../vendor/orchestra/testbench-core/laravel';
        $migrationDirectory = realpath(__DIR__ . '/../vendor/pionect/daalder/database/migrations');
        $migrations = array_diff(scandir($migrationDirectory), ['..', '.']);
        foreach ($migrations as $migration) {
            copy($migrationDirectory . '/' . $migration, $orchestra . '/database/migrations/' . $migration);
        }

        copy(__DIR__ . '/../vendor/pionect/daalder/tests/storage/oauth-private.key', $orchestra . '/storage/oauth-private.key');
        copy(__DIR__ . '/../vendor/pionect/daalder/tests/storage/oauth-public.key', $orchestra . '/storage/oauth-public.key');

        // Setup default database to use sqlite :memory:
//        $app['config']->set('database.default', 'testbench');
//        $app['config']->set('database.connections.testbench', [
//            'driver'   => 'mysql',
//            'database' => 'unit_tests',
//            'prefix'   => '',
//            'host'     => 'localhost',
//            'port'     => '3306',
//            'username' => 'root'
//        ]);
    }

    protected function getPackageProviders($app): array
    {
        return [
            DaalderServiceProvider::class,
            ScoutServiceProvider::class,
            ElasticScoutConfigServiceProvider::class,
            PassportServiceProvider::class,
            PermissionServiceProvider::class,
            TranslatableServiceProvider::class,
            BusinessCentralServiceProvider::class,
        ];
    }

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        //$this->loadMigrationsFrom('vendor/pionect/daalder/database/migrations');
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        // and other test setup steps you need to perform
    }
}