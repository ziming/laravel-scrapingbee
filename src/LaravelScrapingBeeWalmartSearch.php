<?php

declare(strict_types=1);

namespace Ziming\LaravelScrapingBee;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Traits\Conditionable;

final class LaravelScrapingBeeWalmartSearch
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
            'scrapingbee.walmart_search_base_url',
            'https://app.scrapingbee.com/api/v1/walmart/search'
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
     * https://www.scrapingbee.com/documentation/walmart/#light_request_WalmartAPISearch
     */
    public function lightRequest(bool $lightRequest = true): self
    {
        $this->params['light_request'] = $lightRequest;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/walmart/#query
     */
    public function query(string $query): self
    {
        $this->params['query'] = $query;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/walmart/#page
     */
    public function page(int $page): self
    {
        $this->params['page'] = $page;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/walmart/#min_price
     */
    public function minPrice(int $minPrice): self
    {
        $this->params['min_price'] = $minPrice;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/walmart/#max_price
     */
    public function maxPrice(int $maxPrice): self
    {
        $this->params['max_price'] = $maxPrice;

        return $this;
    }

    /*
     * https://www.scrapingbee.com/documentation/walmart/#sort_by
     */
    public function sortBy(string $sortBy): self
    {
        $this->params['sort_by'] = $sortBy;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/walmart/#device_WalmartAPISearch
     */
    public function device(string $device): self
    {
        $this->params['device'] = $device;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/walmart/#domain_WalmartAPISearch
     */
    public function domain(string $domain): self
    {
        $this->params['domain'] = $domain;

        return $this;
    }

    /*
     * https://www.scrapingbee.com/documentation/walmart/#domain_WalmartAPISearch
     */
    public function fulfillmentSpeed(string $fulfillmentSpeed): self
    {
        $this->params['fulfillment_speed'] = $fulfillmentSpeed;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/walmart/#fulfillment_type
     */
    public function fulfillmentType(string $fulfillmentType): self
    {
        $this->params['fulfillment_type'] = $fulfillmentType;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/walmart/#delivery_zip
     */
    public function deliveryZip(string $deliveryZip): self
    {
        $this->params['delivery_zip'] = $deliveryZip;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/walmart/#store_id
     */
    public function storeId(string $storeId): self
    {
        $this->params['store_id'] = $storeId;

        return $this;
    }

    public function addHtml(): self
    {
        $this->params['add_html'] = true;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/google/#extra_params
     */
    public function extraParams(array $extraParams): self
    {
        $this->params['extra_params'] = http_build_query($extraParams);

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/google/#add_html
     */
    public function addHtml(): self
    {
        $this->params['add_html'] = true;

        return $this;
    }

    /*
     * If the API hasn't caught up and you need to support a new ScrapingBee parameter,
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
