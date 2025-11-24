<?php

declare(strict_types=1);

namespace Ziming\LaravelScrapingBee;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Traits\Conditionable;

final class LaravelScrapingBeeChatGpt
{
    use Conditionable;

    private readonly string $baseUrl;
    private readonly string $apiKey;

    private array $params = [];
    private array $headers = [];

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
            'scrapingbee.chatgpt_base_url',
            'https://app.scrapingbee.com/api/v1/chatgpt'
        );
    }

    /**
     * @throws ConnectionException
     */
    public function get(string $url): Response
    {
        $this->params['api_key'] = $this->apiKey;
        $response = Http::get($this->baseUrl, $this->params);
        $this->reset();

        return $response;
    }

    /**
     * https://www.scrapingbee.com/documentation/chatgpt/?fpr=php-laravel#prompt
     */
    public function prompt(string $prompt): self
    {
        $this->params['prompt'] = $prompt;

        return $this;
    }

    public function addHtml(): self
    {
        $this->params['add_html'] = true;

        return $this;
    }

    public function countryCode(string $countryCode): self
    {
        $this->params['country_code'] = $countryCode;

        return $this;
    }

    public function webSearch(): self
    {
        $this->params['search'] = true;

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
