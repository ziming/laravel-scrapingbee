<?php

namespace Ziming\LaravelScrapingBee;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Traits\Conditionable;

final class LaravelScrapingBee
{
    use Conditionable;

    private readonly string $baseUrl;
    private readonly string $apiKey;
    private readonly int $timeout;

    private array $params = [];
    private array $headers = [];

    public static function make(?string $apiKey = null, ?int $timeout = null): self
    {
        return new self($apiKey, $timeout);
    }

    public function __construct(?string $apiKey = null, ?int $timeout = null)
    {
        // If somebody pass '' into the constructor, we should use '' as the api key
        // even if it doesn't make sense.
        // If $apiKey is null, then we use the 1 in the config file.
        $this->apiKey = $apiKey ?? config('scrapingbee.api_key');
        $this->timeout = $timeout ?? config('scrapingbee.timeout');

        $this->baseUrl = config(
            'scrapingbee.base_url',
            'https://app.scrapingbee.com/api/v1/'
        );
    }

    private function request(string $method, string $url, array $data = [], string $postContentType = 'application/x-www-form-urlencoded; charset=utf-8'): Response
    {
        // https://www.scrapingbee.com/documentation/#encoding-url
        // urlencode($url) make things fail somehow.
        // My guess is urlencode is run at the Http::get() / Http::post() level already
        $this->params['url'] = $url;

        $this->params['api_key'] = $this->apiKey;

        $http = Http::withHeaders($this->headers)->timeout($this->timeout);

        if ($method === 'POST') {
            // If user never specify content type we use the 1 provided in the official docs.
            $http->withHeaders([
                'Content-Type' => $postContentType,
            ]);

            $response = $http->post($this->baseUrl . '?' . http_build_query($this->params), $data);
        } else {
            // else assume is a GET request
            $response = $http->get($this->baseUrl, $this->params);
        }

        // Reset the params and headers
        $this->reset();

        return $response;
    }

    public function get(string $url): Response
    {
        return $this->request('GET', $url);
    }

    public function post(string $url, array $data = [], string $postContentType = 'application/x-www-form-urlencoded; charset=utf-8'): Response
    {
        return $this->request('POST', $url, $data, $postContentType);
    }

    /**
     * https://www.scrapingbee.com/documentation/#block-ads
     */
    public function blockAds(): self
    {
        $this->params['block_ads'] = true;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/#block-resources
     */
    public function allowResources(): self
    {
        $this->params['block_resources'] = false;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/#custom-cookies
     */
    public function setCustomCookies(array $cookies): self
    {
        $this->params['cookies'] = http_build_query($cookies, '', ';');

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/#geolocation
     */
    public function countryCode(string $countryCode): self
    {
        $this->params['country_code'] = $countryCode;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/#custom-google
     */
    public function customGoogle(): self
    {
        $this->params['custom_google'] = true;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/#device
     */
    public function device(string $device): self
    {
        $this->params['device'] = $device;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/#json_css
     * https://www.scrapingbee.com/documentation/data-extraction/
     */
    public function extractDataFromCssRules(array $cssRules): self
    {
        $this->params['extract_rules'] = json_encode($cssRules, JSON_THROW_ON_ERROR);

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/#json_response
     */
    public function jsonResponse(): self
    {
        $this->params['json_response'] = true;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/#javascript-execution
     */
    public function jsSnippet(string $jsCodeSnippet): self
    {
        $this->params['js_snippet'] = base64_encode($jsCodeSnippet);

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/#javascript-scroll
     */
    public function jsScroll(): self
    {
        $this->params['js_scroll'] = true;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/#javascript-scroll
     */
    public function jsScrollWait(int $milliseconds): self
    {
        $this->params['js_scroll_wait'] = $milliseconds;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/#javascript-scroll
     */
    public function jsScrollCount(int $count): self
    {
        $this->params['js_scroll_count'] = $count;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/#premium-proxy
     */
    public function premiumProxy(): self
    {
        $this->params['premium_proxy'] = true;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/#stealth_proxy
     */
    public function stealthProxy(): self
    {
        $this->params['stealth_proxy'] = true;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/#own_proxy
     */
    public function ownProxy(string $proxy): self
    {
        $this->params['own_proxy'] = $proxy;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/#javascript-rendering
     */
    public function disableJs(): self
    {
        $this->params['render_js'] = false;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/#javascript-execution
     * https://www.scrapingbee.com/documentation/js-scenario/
     */
    public function jsScenario(array $instructions, bool $strict = true): self
    {
        $this->params['js_scenario'] = json_encode([
            'strict' => $strict,
            'instructions' => $instructions,
        ], JSON_THROW_ON_ERROR);

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/#page-source
     */
    public function pageSource(): self
    {
        $this->params['return_page_source'] = true;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/#session_id
     */
    public function session(int $id): self
    {
        $this->params['session_id'] = $id;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/#timeout
     */
    public function timeout(int $milliseconds): self
    {
        $this->params['timeout'] = $milliseconds;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/#screenshot
     */
    public function screenshot(): self
    {
        $this->params['screenshot'] = true;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/#screenshot_selector
     */
    public function screenshotSelector(string $selector): self
    {
        $this->params['screenshot_selector'] = true;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/#screenshot_full_page
     */
    public function screenshotFullPage(): self
    {
        $this->params['screenshot_full_page'] = true;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/#transparent_status_code
     */
    public function transparentHttpStatusCode(): self
    {
        $this->params['transparent_status_code'] = true;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/#wait
     */
    public function wait(int $milliseconds): self
    {
        $this->params['wait'] = $milliseconds;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/#wait-for
     */
    public function waitForCssSelector(string $cssSelector): self
    {
        $this->params['wait_for'] = $cssSelector;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/#wait_browser
     */
    public function waitForBrowser(string $networkCondition = 'domcontentloaded'): self
    {
        $this->params['wait_browser'] = $networkCondition;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/#width_or_height
     */
    public function windowWidth(int $windowWidth): self
    {
        $this->params['window_width'] = $windowWidth;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/#width_or_height
     */
    public function windowHeight(int $windowHeight): self
    {
        $this->params['window_height'] = $windowHeight;

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/data-extraction/#output
     */
    public function output(string $output): self
    {
        $this->params['output'] = $output;

        return $this;
    }

    /*
     * This is for the situation if our API did not catch up and you want to add a new parameter
     * that Scrapingbee supports
     * like ->setParam('stealth_proxy', true) for example
     * stealth proxy feature is in beta and hence I have not add a dedicated method to support it yet
     */
    public function setParam(string $key, mixed $value): self
    {
        $this->params[$key] = $value;

        return $this;
    }

    public function setParams(array $params): self
    {
        $this->params = $params;

        return $this;
    }

    /*
     * Just in case there are new params that are not covered by the convenience params methods
     */
    public function addParams(array $params): self
    {
        $this->params = array_merge_recursive($this->params, $params);

        return $this;
    }

    public function setHeaders(array $headers): self
    {
        $this->headers = array_combine(
            array_map(fn ($key) => 'Spb-'. $key, array_keys($headers)),
            $headers
        );

        $this->params['forward_headers'] = true;

        return $this;
    }

    public function pureHeadersForwarding(): self
    {
        $this->params['forward_headers_pure'] = true;
        unset($this->params['forward_headers']);

        return $this;
    }

    private function reset(): self
    {
        $this->params = [];
        $this->headers = [];

        return $this;
    }

    /**
     * https://www.scrapingbee.com/documentation/#UsageEndpoint
     */
    public function usageStatistics(): Response
    {
        return Http::get($this->baseUrl . 'usage', [
            'api_key' => $this->apiKey,
        ]);
    }
}
