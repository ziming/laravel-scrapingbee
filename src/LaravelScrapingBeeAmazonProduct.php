<?php

namespace Ziming\LaravelScrapingBee;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Traits\Conditionable;

final class LaravelScrapingBeeAmazonProduct
{
    use Conditionable;

    private readonly string $baseUrl;
    private readonly string $apiKey;

    private array $params = [];

    public static function make(#[\SensitiveParameter] ?string $apiKey = null): self
    {
        return new self($apiKey);
    }

    public function __construct(#[\SensitiveParameter] ?string $apiKey = null)
    {
        // If somebody pass '' into the constructor, we should use '' as the api key
        // even if it doesn't make sense.
        // If $apiKey is null, then we use the 1 in the config file.
        $this->apiKey = $apiKey ?? config('scrapingbee.api_key');

        $this->baseUrl = config(
            'scrapingbee.amazon_product_base_url',
            'https://app.scrapingbee.com/api/v1/amazon/product'
        );
    }

    /**
     * @throws ConnectionException
     */
    public function get(): Response
    {
        $this->params['api_key'] = $this->apiKey;
        $response = Http::get($this->baseUrl, $this->params);
        $this->reset();

        return $response;
    }

    /**
     * https://www.scrapingbee.com/documentation/amazon/#light_request
     */
    public function lightRequest(bool $lightRequest = true): self
    {
        $this->params['light_request'] = $lightRequest;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/amazon/#query
     */
    public function query(string $productAsin): self
    {
        $this->params['query'] = $productAsin;

        return $this;
    }

    /**
     * Alias for query method
     *
     * ASIN stands for Amazon Standard Identification Number
     *
     * @see query()
     */
    public function asin(string $productAsin): self
    {
        $this->params['query'] = $productAsin;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/amazon/#device_AmazonProduct
     */
    public function device(string $device): self
    {
        $this->params['device'] = $device;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/amazon/#domain
     */
    public function domain(string $domain): self
    {
        $this->params['domain'] = $domain;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/amazon/#country_AmazonProduct
     */
    public function country(string $country): self
    {
        $this->params['country'] = $country;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/amazon/#zip_code_AmazonProduct
     */
    public function zipCode(string $zipCode): self
    {
        $this->params['zip_code'] = $zipCode;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/amazon/#language
     */
    public function language(string $language): self
    {
        $this->params['language'] = $language;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/amazon/#currency
     */
    public function currency(string $currency): self
    {
        $this->params['currency'] = $currency;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/walmart/#add_html_WalmartAPIProduct
     */
    public function addHtml(): self
    {
        $this->params['add_html'] = true;

        return $this;
    }

    /*
     * If the API hasn't caught up, and you need to support a new ScrapingBee parameter,
     * you can set it using this method.
     */
    public function setParam(string $key, mixed $value): self
    {
        $this->params[$key] = $value;

        return $this;
    }

    private function reset(): self
    {
        $this->params = [];

        return $this;
    }
}
