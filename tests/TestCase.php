<?php

declare(strict_types=1);

namespace Ziming\LaravelScrapingBee\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Ziming\LaravelScrapingBee\LaravelScrapingBeeServiceProvider;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            LaravelScrapingBeeServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
    }
}
