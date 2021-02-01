<?php

namespace Daalder\BusinessCentral\Tests;

use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function getPackageProviders($app): array
    {
        return [
            'Daalder\BusinessCentral\BusinessCentralServiceProvider'
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