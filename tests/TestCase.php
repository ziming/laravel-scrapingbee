<?php

namespace Ziming\LaravelScrapingBee\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Ziming\LaravelScrapingBee\LaravelScrapingBeeServiceProvider;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();
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
    }
}
