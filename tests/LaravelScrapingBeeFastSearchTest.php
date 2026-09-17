<?php

declare(strict_types=1);

namespace Ziming\LaravelScrapingBee\Tests;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Ziming\LaravelScrapingBee\LaravelScrapingBeeFastSearch;

class LaravelScrapingBeeFastSearchTest extends TestCase
{
    public function test_it_sends_fast_search_request_with_bearer_token(): void
    {
        config()->set('scrapingbee.api_key', 'test-api-key');
        config()->set('scrapingbee.fast_search_base_url', 'https://example.test/fast_search');

        Http::fake([
            '*' => Http::response(['status' => 'done']),
        ]);

        $response = LaravelScrapingBeeFastSearch::make()
            ->search('pizza & pasta + drinks: nearby')
            ->countryCode('us')
            ->page(2)
            ->tag('docs-example')
            ->get();

        $this->assertSame(['status' => 'done'], $response->json());

        Http::assertSent(function (Request $request): bool {
            parse_str((string) parse_url($request->url(), PHP_URL_QUERY), $query);

            return str_starts_with($request->url(), 'https://example.test/fast_search?')
                && $request->hasHeader('Authorization', 'Bearer test-api-key')
                && $query === [
                    'search' => 'pizza & pasta + drinks: nearby',
                    'country_code' => 'us',
                    'page' => '2',
                    'tag' => 'docs-example',
                ];
        });
    }
}
