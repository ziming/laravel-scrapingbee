<?php

declare(strict_types=1);

namespace Ziming\LaravelScrapingBee;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Ziming\LaravelScrapingBee\LaravelScrapingBee
 */
class LaravelScrapingBeeFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-scrapingbee';
    }
}
