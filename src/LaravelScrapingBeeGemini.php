<?php

declare(strict_types=1);

namespace Ziming\LaravelScrapingBee;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Traits\Conditionable;

final class LaravelScrapingBeeGemini
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
            'scrapingbee.gemini_base_url',
            'https://app.scrapingbee.com/api/v1/gemini'
        );
    }

    /**
     * @throws ConnectionException
     */
    public function get(): Response
    {
        $response = Http::withToken($this->apiKey)->get($this->baseUrl, $this->params);
        $this->reset();

        return $response;
    }

    /**
     * https://www.scrapingbee.com/documentation/gemini/?fpr=php-laravel#prompt
     */
    public function prompt(string $prompt): self
    {
        $this->params['prompt'] = $prompt;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/gemini/?fpr=php-laravel#add_html
     */
    public function addHtml(): self
    {
        $this->params['add_html'] = true;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/gemini/?fpr=php-laravel#country_code
     */
    public function countryCode(string $countryCode): self
    {
        $this->params['country_code'] = $countryCode;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/gemini/?fpr=php-laravel#tag
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
