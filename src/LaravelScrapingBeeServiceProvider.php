<?php

namespace Ziming\LaravelScrapingBee;

use Illuminate\Support\Facades\Http;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelScrapingBeeServiceProvider extends PackageServiceProvider
{
    public function boot()
    {
        parent::boot();

        // Add initial scrapingbee http macro still exploring, not using it yet
        Http::macro('scrapingbee', function () {
            return Http::baseUrl(config('scrapingbee.base_url'));
        });
    }

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-scrapingbee')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->bind('laravel-scrapingbee', LaravelScrapingBee::class);
    }
}
