<?php

declare(strict_types=1);

namespace Ziming\LaravelScrapingBee;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelScrapingBeeServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-scrapingbee')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->bind('laravel-scrapingbee', LaravelScrapingBee::class);
    }
}
