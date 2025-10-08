<?php

namespace Ziming\LaravelScrapingBee;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Traits\Conditionable;

final class LaravelScrapingBeeWalmartProduct
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
            'scrapingbee.walmart_product_base_url',
            'https://app.scrapingbee.com/api/v1/walmart/product'
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
     * https://www.scrapingbee.com/documentation/walmart/#light_request_WalmartAPIProduct
     */
    public function lightRequest(bool $lightRequest = true): self
    {
        $this->params['light_request'] = $lightRequest;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/walmart/#product_id
     */
    public function productId(string $storeId): self
    {
        $this->params['product_id'] = $storeId;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/walmart/#domain_WalmartAPIProduct
     */
    public function domain(string $domain): self
    {
        $this->params['domain'] = $domain;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/walmart/#delivery_zip_WalmartAPIProduct
     */
    public function deliveryZip(string $deliveryZip): self
    {
        $this->params['delivery_zip'] = $deliveryZip;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/walmart/#store_id_WalmartAPIProduct
     */
    public function storeId(string $storeId): self
    {
        $this->params['store_id'] = $storeId;

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
