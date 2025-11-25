<?php

declare(strict_types=1);

namespace Ziming\LaravelScrapingBee;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Traits\Conditionable;

final class LaravelScrapingBeeYouTubeSearch
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
        $this->apiKey = $apiKey ?? config('scrapingbee.api_key');

        $this->baseUrl = config(
            'scrapingbee.youtube_search_base_url',
            'https://app.scrapingbee.com/api/api/v1/youtube/search'
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
     * required
     * https://www.scrapingbee.com/documentation/youtube/?fpr=php-laravel#search
     */
    public function search(string $query): self
    {
        $this->params['search'] = $query;
        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/youtube/?fpr=php-laravel#360
     */
    public function only360(): self
    {
        $this->params['360'] = true;
        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/youtube/?fpr=php-laravel#3d
     */
    public function only3d(): self
    {
        $this->params['3d'] = true;
        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/youtube/?fpr=php-laravel#4k
     */
    public function only4k(): self
    {
        $this->params['4k'] = true;
        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/youtube/?fpr=php-laravel#creative_commons
     */
    public function creativeCommons(): self
    {
        $this->params['creative_commons'] = true;
        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/youtube/?fpr=php-laravel#duration
     */
    public function duration(string $value): self
    {
        $this->params['duration'] = $value;
        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/youtube/?fpr=php-laravel#hd
     */
    public function onlyHd(): self
    {
        $this->params['hd'] = true;
        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/youtube/?fpr=php-laravel#hdr
     */
    public function onlyHdr(): self
    {
        $this->params['hdr'] = true;
        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/youtube/?fpr=php-laravel#live
     */
    public function live(): self
    {
        $this->params['live'] = true;
        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/youtube/?fpr=php-laravel#location
     */
    public function location(): self
    {
        $this->params['location'] = true;
        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/youtube/?fpr=php-laravel#purchased
     */
    public function purchased(): self
    {
        $this->params['purchased'] = true;
        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/youtube/?fpr=php-laravel#sort_by
     */
    public function sortBy(string $value): self
    {
        $this->params['sort_by'] = $value;
        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/youtube/?fpr=php-laravel#subtitles
     */
    public function subtitles(): self
    {
        $this->params['subtitles'] = true;
        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/youtube/?fpr=php-laravel#type
     */
    public function type(string $value): self
    {
        $this->params['type'] = $value;
        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/youtube/?fpr=php-laravel#upload_date
     */
    public function uploadDate(string $value): self
    {
        $this->params['upload_date'] = $value;
        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/youtube/?fpr=php-laravel#vr180
     */
    public function onlyVr180(bool $value = true): self
    {
        $this->params['vr180'] = $value;
        return $this;
    }

    /**
     * Set any extra parameters directly.
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
