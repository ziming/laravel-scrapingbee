# A PHP Laravel Library for [ScrapingBee](https://www.scrapingbee.com?fpr=laravel-scrapingbee)


[![Latest Version on Packagist](https://img.shields.io/packagist/v/ziming/laravel-scrapingbee.svg?style=flat-square)](https://packagist.org/packages/ziming/laravel-scrapingbee)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/ziming/laravel-scrapingbee/run-tests?label=tests)](https://github.com/ziming/laravel-scrapingbee/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/ziming/laravel-scrapingbee/Check%20&%20fix%20styling?label=code%20style)](https://github.com/ziming/laravel-scrapingbee/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/ziming/laravel-scrapingbee.svg?style=flat-square)](https://packagist.org/packages/ziming/laravel-scrapingbee)

A PHP Laravel Package for [ScrapingBee](https://www.scrapingbee.com?fpr=laravel-scrapingbee)

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
];
```

## Usage

```php
$scrapingBeeClient = Ziming\LaravelScrapingBee::make();

$response = $scrapingBeeClient->blockAds()
    ->premiumProxy()
    ->get('https://www.scrapingbee.com')

// Look at the source code of `src/LaravelScrapingBee.php` for now. Proper Docs will be added later.

// https://github.com/ziming/laravel-scrapingbee/blob/main/src/LaravelScrapingBee.php
```

## Testing

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Ziming](https://github.com/ziming)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
