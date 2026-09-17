<?php

declare(strict_types=1);

namespace Ziming\LaravelScrapingBee;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Traits\Conditionable;

final class LaravelScrapingBeeShopee
{
    use Conditionable;

    private readonly string $baseUrl;
    private readonly string $apiKey;
    private readonly int $timeout;

    private array $params = [];

    public static function make(#[\SensitiveParameter] ?string $apiKey = null, ?int $timeout = null): self
    {
        return new self($apiKey, $timeout);
    }

    public function __construct(#[\SensitiveParameter] ?string $apiKey = null, ?int $timeout = null)
    {
        $this->apiKey = $apiKey ?? config('scrapingbee.api_key');
        $this->timeout = $timeout ?? config('scrapingbee.timeout');

        $this->baseUrl = config(
            'scrapingbee.shopee_base_url',
            'https://app.scrapingbee.com/api/v1/shopee'
        );
    }

    /**
     * @throws ConnectionException
     */
    public function get(): Response
    {
        $response = Http::withToken($this->apiKey)
            ->timeout($this->timeout)
            ->get($this->baseUrl, $this->params);

        $this->reset();

        return $response;
    }

    /**
     * https://www.scrapingbee.com/documentation/shopee/#url
     */
    public function url(string $url): self
    {
        $this->params['url'] = $url;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/shopee/#add-html
     */
    public function addHtml(bool $addHtml = true): self
    {
        $this->params['add_html'] = $addHtml;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/shopee/#tag
     */
    public function tag(string $tag): self
    {
        $this->params['tag'] = $tag;

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
