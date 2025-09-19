<?php

namespace Ziming\LaravelScrapingBee;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Traits\Conditionable;

final class LaravelScrapingBeeGoogleSearch
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
            'scrapingbee.google_search_base_url',
            'https://app.scrapingbee.com/api/v1/store/google'
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
     * https://www.scrapingbee.com/documentation/google/#search
     */
    public function search(string $query): self
    {
        $this->params['search'] = $query;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/google/#search_type
     */
    public function searchType(string $type): self
    {
        $this->params['search_type'] = $type;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/google/#country_code
     */
    public function countryCode(string $countryCode): self
    {
        $this->params['country_code'] = $countryCode;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/google/#device
     */
    public function device(string $device): self
    {
        $this->params['device'] = $device;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/google/#nb_results
     */
    #[\Deprecated]
    public function nbResults(int $count): self
    {
        $this->params['nb_results'] = $count;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/google/#page
     */
    public function page(int $pageNumber): self
    {
        $this->params['page'] = $pageNumber;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/google/#language
     */
    public function language(string $language): self
    {
        $this->params['language'] = $language;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/google/#light_request
     */
    public function lightRequest(bool $lightRequest = true): self
    {
        $this->params['light_request'] = $lightRequest;

        return $this;
    }

    public function autoCorrection(): self
    {
        $this->params['nfpr'] = true;

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
