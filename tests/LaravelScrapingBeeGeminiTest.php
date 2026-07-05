<?php

declare(strict_types=1);

namespace Ziming\LaravelScrapingBee\Tests;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Ziming\LaravelScrapingBee\LaravelScrapingBeeGemini;

class LaravelScrapingBeeGeminiTest extends TestCase
{
    public function test_it_sends_documented_gemini_parameters_with_bearer_authentication(): void
    {
        config()->set('scrapingbee.api_key', 'test-api-key');
        config()->set('scrapingbee.gemini_base_url', 'https://example.test/api/v1/gemini');

        Http::fake([
            'https://example.test/api/v1/gemini*' => Http::response(['results_text' => 'Done.']),
        ]);

        $response = LaravelScrapingBeeGemini::make()
            ->prompt('Best programming languages for data science')
            ->addHtml()
            ->countryCode('US')
            ->tag('research')
            ->get();

        $this->assertTrue($response->ok());

        Http::assertSent(function (Request $request): bool {
            parse_str(parse_url($request->url(), PHP_URL_QUERY) ?: '', $query);

            return $request->method() === 'GET'
                && str_starts_with($request->url(), 'https://example.test/api/v1/gemini?')
                && $request->hasHeader('Authorization', 'Bearer test-api-key')
                && $query === [
                    'prompt' => 'Best programming languages for data science',
                    'add_html' => '1',
                    'country_code' => 'US',
                    'tag' => 'research',
                ];
        });
    }

    public function test_it_resets_parameters_after_a_request(): void
    {
        config()->set('scrapingbee.gemini_base_url', 'https://example.test/api/v1/gemini');

        Http::fake([
            'https://example.test/api/v1/gemini*' => Http::response(['results_text' => 'Done.']),
        ]);

        $client = LaravelScrapingBeeGemini::make('test-api-key');

        $client->prompt('First prompt')->get();
        $client->get();

        Http::assertSent(function (Request $request): bool {
            return str_contains($request->url(), 'prompt=First');
        });

        Http::assertSent(function (Request $request): bool {
            return $request->url() === 'https://example.test/api/v1/gemini';
        });
    }
}
