# A PHP Laravel Library for [ScrapingBee](https://www.scrapingbee.com?fpr=php-laravel)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ziming/laravel-scrapingbee.svg?style=flat-square)](https://packagist.org/packages/ziming/laravel-scrapingbee)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/ziming/laravel-scrapingbee/run-tests?label=tests)](https://github.com/ziming/laravel-scrapingbee/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/ziming/laravel-scrapingbee/Check%20&%20fix%20styling?label=code%20style)](https://github.com/ziming/laravel-scrapingbee/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/ziming/laravel-scrapingbee.svg?style=flat-square)](https://packagist.org/packages/ziming/laravel-scrapingbee)
[![RINGER](https://www.ringerhq.com/images/get-support-on-ringer.svg)](https://www.ringerhq.com/i/ziming/laravel-scrapingbee)

A PHP Laravel Package for [ScrapingBee](https://www.scrapingbee.com?fpr=php-laravel)

If you wanted to support my work you can use my referral link to be a paid customer of [ScrapingBee](https://www.scrapingbee.com?fpr=php-laravel)

## Installation

You can install the package via composer:

```bash
composer require ziming/laravel-scrapingbee
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Ziming\LaravelScrapingBee\LaravelScrapingBeeServiceProvider" --tag="laravel-scrapingbee-config"
```

This is the contents of the published config file:

```php
return [
    'api_key' => env('SCRAPINGBEE_API_KEY'),
    'base_url' => env('SCRAPINGBEE_BASE_URL', 'https://app.scrapingbee.com/api/v1/'),
    'timeout' => env('SCRAPINGBEE_TIMEOUT', 120),
];
```

## Usage

### The Generic ScrapingBee Client

```php
$scrapingBeeClient = Ziming\LaravelScrapingBee\LaravelScrapingBee::make();

$response = $scrapingBeeClient->blockAds()
    ->when(now()->weekOfMonth === 4, function (LaravelScrapingBee $scrapingBeeClient): LaravelScrapingBee {
        // if it is last week of the month, spam premium proxy to use up credits!
        return $scrapingBeeClient->premiumProxy();
    })
    ->jsonResponse()
    ->jsScenario([
        ['click' => '#button_id'],
        ['wait' => 1000],
        ['wait_for' => '#slow_div'],
        ['scroll_x' => 1000],
        ['scroll_y' => 1000],
        ['fill' => ['#input_1','value_1']],
        ['evaluate' => 'console.log(window);'],
])->get('https://www.scrapingbee.com')
```

Look at the source code of `src/LaravelScrapingBee.php` for the other methods (link below). Methods that return `$this` are chainable. An example is the `blockAds()` method you saw above. Meanwhile methods such as `get()`, `post()`, `usageStatistics()` returns you an `Illuminate\Http\Client\Response` object if no exceptions are thrown.

[LaravelScrapingBee.php](https://github.com/ziming/laravel-scrapingbee/blob/main/src/LaravelScrapingBee.php)

If for some reason you prefer to set all parameters at once you may wish to use the `setParams() or addParams()` method. Take note that these methods simply takes in an array and sets the parameters as is. So for the methods that does something extra before setting the parameter you would have to do them yourselves now if you chose this path.

An example is shown below:

```php
$scrapingBeeClient = Ziming\LaravelScrapingBee\LaravelScrapingBee::make();

$response = $scrapingBeeClient->setParams([
        'js_scenario' => json_encode([
            'instructions' => [
                ['click' => '#button_id'],
                ['wait' => 1000],
                ['wait_for' => '#slow_div'],
                ['scroll_x' => 1000],
                ['scroll_y' => 1000],
                ['fill' => ['#input_1','value_1']],
                ['evaluate' => 'console.log(window);']
            ]
        ]),
        'block_ads' => true,
        'json_response' => true,
    ])->get('https://www.scrapingbee.com')
```

### The Google Search ScrapingBee Client

```php
$googleSearchScrapingBeeClient = Ziming\LaravelScrapingBee\LaravelScrapingBeeGoogleSearch::make();

$response = $googleSearchScrapingBeeClient
    ->nbResults(8)
    ->page(1)
    ->search('scrapingbee')
    ->get();
```
Look at the source code of `src/LaravelScrapingBeeGoogleSearch.php` for the other methods (link below).

[LaravelScrapingBeeGoogleSearch.php](https://github.com/ziming/laravel-scrapingbee/blob/main/src/LaravelScrapingBeeGoogleSearch.php)

## Testing

Currently, there are no tests. But if there are tests in the future, you can run the command below to execute the testcases.

```bash
composer test
```

## Contributing

1 way to contribute is to donate. The other alternative is to use my referral link to [Scrapingbee](https://www.scrapingbee.com?fpr=php-laravel) & be a paying customer

As for contributing to this codebase, please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Ziming](https://github.com/ziming)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
