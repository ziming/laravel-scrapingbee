<?php

namespace Ziming\LaravelScrapingBee\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Ziming\LaravelScrapingBee\LaravelScrapingBeeServiceProvider;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        /*
        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Ziming\\LaravelScrapingBee\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
        */
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelScrapingBeeServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        include_once __DIR__.'/../database/migrations/create_laravel-scrapingbee_table.php.stub';
        (new \CreatePackageTable())->up();
        */
    }
}
