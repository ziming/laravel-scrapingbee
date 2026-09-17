<?php

declare(strict_types=1);

namespace Ziming\LaravelScrapingBee\Tests;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Ziming\LaravelScrapingBee\LaravelScrapingBeeChatGpt;

class ChatGptTest extends TestCase
{
    #[Test]
    public function it_sends_a_chatgpt_request_with_bearer_auth_and_supported_parameters(): void
    {
        config()->set('scrapingbee.api_key', 'test-api-key');

        Http::fake([
            'https://app.scrapingbee.com/api/v1/chatgpt*' => Http::response([
                'results_text' => 'Hello from ChatGPT',
            ]),
        ]);

        $response = LaravelScrapingBeeChatGpt::make()
            ->prompt('Explain renewable energy in 100 words')
            ->search()
            ->addHtml()
            ->countryCode('us')
            ->tag('docs-example')
            ->get();

        $this->assertSame('Hello from ChatGPT', $response->json('results_text'));

        Http::assertSent(function (Request $request): bool {
            return str_starts_with($request->url(), 'https://app.scrapingbee.com/api/v1/chatgpt?')
                && $request->hasHeader('Authorization', 'Bearer test-api-key')
                && $request->data() === [
                    'prompt' => 'Explain renewable energy in 100 words',
                    'search' => true,
                    'add_html' => true,
                    'country_code' => 'us',
                    'tag' => 'docs-example',
                ];
        });
    }

    #[Test]
    public function it_resets_parameters_after_each_chatgpt_request(): void
    {
        config()->set('scrapingbee.api_key', 'test-api-key');

        Http::fake([
            'https://app.scrapingbee.com/api/v1/chatgpt*' => Http::response([]),
        ]);

        $client = LaravelScrapingBeeChatGpt::make();

        $client->prompt('First prompt')->webSearch()->get();
        $client->prompt('Second prompt')->get();

        Http::assertSent(function (Request $request): bool {
            $data = $request->data();

            return ($data['prompt'] ?? null) === 'Second prompt'
                && ! array_key_exists('search', $data);
        });
    }

    #[Test]
    public function it_supports_explicit_boolean_values_and_the_web_search_alias(): void
    {
        Http::fake([
            'https://app.scrapingbee.com/api/v1/chatgpt*' => Http::response([]),
        ]);

        LaravelScrapingBeeChatGpt::make('test-api-key')
            ->prompt('Do not search the web')
            ->webSearch(false)
            ->addHtml(false)
            ->get();

        Http::assertSent(fn (Request $request): bool => $request->data() === [
            'prompt' => 'Do not search the web',
            'search' => false,
            'add_html' => false,
        ]);
    }
}
