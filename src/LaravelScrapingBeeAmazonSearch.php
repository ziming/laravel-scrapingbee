<?php

declare(strict_types=1);

namespace Ziming\LaravelScrapingBee;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Traits\Conditionable;

final class LaravelScrapingBeeAmazonSearch
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
            'https://app.scrapingbee.com/api/v1/amazon/search'
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
     * https://www.scrapingbee.com/documentation/amazon/#light_request_AmazonSearch
     */
    public function lightRequest(bool $lightRequest = true): self
    {
        $this->params['light_request'] = $lightRequest;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/amazon/#query_AmazonSearch
     */
    public function query(string $query): self
    {
        $this->params['query'] = $query;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/amazon/#start_page
     */
    public function startPage(int $page): self
    {
        $this->params['start_page'] = $page;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/amazon/#pages
     */
    public function pages(int $pages): self
    {
        $this->params['pages'] = $pages;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/amazon/#sort_by
     */
    public function sortBy(string $sortBy): self
    {
        $this->params['sort_by'] = $sortBy;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/amazon/#device_AmazonSearch
     */
    public function device(string $device): self
    {
        $this->params['device'] = $device;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/amazon/#domain_AmazonSearch
     */
    public function domain(string $domain): self
    {
        $this->params['domain'] = $domain;

        return $this;
    }

    /*
     * https://www.scrapingbee.com/documentation/amazon/#country_AmazonSearch
     */
    public function country(string $isoCountryCode): self
    {
        $this->params['country'] = $isoCountryCode;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/amazon/#zip_code_AmazonSearch
     */
    public function zipCode(string $zipCode): self
    {
        $this->params['zip_code'] = $zipCode;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/amazon/#language_AmazonSearch
     */
    public function language(string $language): self
    {
        $this->params['language'] = $language;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/amazon/#currency_AmazonSearch
     */
    public function currency(string $currency): self
    {
        $this->params['currency'] = $currency;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/amazon/#category_id
     */
    public function categoryId(string $categoryId): self
    {
        $this->params['category_id'] = $categoryId;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/amazon/#merchant_id
     */
    public function merchantId(string $merchantId): self
    {
        $this->params['merchant_id'] = $merchantId;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/amazon/#autoselect_variant
     */
    public function autoSelectVariant(bool $autoSelectVariant = true): self
    {
        $this->params['autoselect_variant'] = $autoSelectVariant;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/amazon/#add_html_AmazonSearch
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
