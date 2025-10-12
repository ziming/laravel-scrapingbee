# A PHP Laravel Library for [ScrapingBee](https://www.scrapingbee.com?fpr=php-laravel)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ziming/laravel-scrapingbee.svg?style=flat-square)](https://packagist.org/packages/ziming/laravel-scrapingbee)
[![Total Downloads](https://img.shields.io/packagist/dt/ziming/laravel-scrapingbee.svg?style=flat-square)](https://packagist.org/packages/ziming/laravel-scrapingbee)

A PHP Laravel Package for [ScrapingBee](https://www.scrapingbee.com?fpr=php-laravel)

If you wish to support my work you can use my [referral link to create a free account, I get a small reward if you upgrade to a paid plan in the future](https://www.scrapingbee.com?fpr=php-laravel).

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
    'timeout' => env('SCRAPINGBEE_TIMEOUT', 140),

    'google_search_base_url' => env('SCRAPINGBEE_GOOGLE_SEARCH_BASE_URL', 'https://app.scrapingbee.com/api/v1/store/google'),

    'walmart_search_base_url' => env('SCRAPINGBEE_WALMART_SEARCH_BASE_URL', 'https://app.scrapingbee.com/api/v1/walmart/search'),
    'walmart_product_base_url' => env('SCRAPINGBEE_WALMART_PRODUCT_BASE_URL', 'https://app.scrapingbee.com/api/v1/walmart/product'),

    'amazon_search_base_url' => env('SCRAPINGBEE_AMAZON_SEARCH_BASE_URL', 'https://app.scrapingbee.com/api/v1/amazon/search'),
    'amazon_product_base_url' => env('SCRAPINGBEE_AMAZON_PRODUCT_BASE_URL', 'https://app.scrapingbee.com/api/v1/amazon/product'),
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
    //->aiQuery('top 5 blog posts') // AI Query Feature!
    //->aiExtractRules(['title' => 'title of post', 'summary' => 'summary of post']) // AI Extract Feature!
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
    ->page(1)
    ->search('scrapingbee')
    ->get();
```
Look at the source code of `src/LaravelScrapingBeeGoogleSearch.php` for the other methods (link below).

[LaravelScrapingBeeGoogleSearch.php](https://github.com/ziming/laravel-scrapingbee/blob/main/src/LaravelScrapingBeeGoogleSearch.php)

### Walmart ScrapingBee Clients

#### The Walmart Search ScrapingBee Client

```php
$walmartSearchScrapingBeeClient = Ziming\LaravelScrapingBee\LaravelScrapingBeeWalmartSearch::make();

$response = $walmartSearchScrapingBeeClient
    ->query('iPhone')
    ->minPrice(100)
    ->maxPrice(1000)
    ->fulfillmentType('in_store')
    ->fulfillmentSpeed('today')
    ->domain('walmart.ca')
    ->sortBy('price_low')
    ->page(2)
    ->get();
```
Look at the source code of `src/LaravelScrapingBeeWalmartSearch.php` for the other methods (link below).

[LaravelScrapingBeeWalmartSearch.php](https://github.com/ziming/laravel-scrapingbee/blob/main/src/LaravelScrapingBeeWalmartSearch.php)

#### The Walmart Product ScrapingBee Client

```php
$walmartProductScrapingBeeClient = Ziming\LaravelScrapingBee\LaravelScrapingBeeWalmartProduct::make();

$response = $walmartProductScrapingBeeClient
    ->productId('5491199371')
    ->domain('walmart.ca')
    ->deliveryZip('10001')
    ->storeId('3520')
    ->get();
```
Look at the source code of `src/LaravelScrapingBeeWalmartProduct.php` for the other methods (link below).

[LaravelScrapingBeeWalmartProduct.php](https://github.com/ziming/laravel-scrapingbee/blob/main/src/LaravelScrapingBeeWalmartProduct.php)

## Testing

Currently, there are no tests as it uses credits. But if there are tests in the future, you can run the command below to execute the testcases.

```bash
composer test
```

## Contributing

You may see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Ziming](https://github.com/ziming)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
