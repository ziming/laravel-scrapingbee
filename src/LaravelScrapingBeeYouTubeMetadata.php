<?php

declare(strict_types=1);

namespace Ziming\LaravelScrapingBee;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Traits\Conditionable;

final class LaravelScrapingBeeYouTubeMetadata
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
            'scrapingbee.youtube_metadata_base_url',
            'https://app.scrapingbee.com/api/v1/metadata'
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
     * Required
     * https://www.scrapingbee.com/documentation/youtube/?fpr=php-laravel#video_id_YouTubeMetadata
     */
    public function videoId(string $videoId): self
    {
        $this->params['video_id'] = $videoId;

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
