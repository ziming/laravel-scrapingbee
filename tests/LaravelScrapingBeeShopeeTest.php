<?php

declare(strict_types=1);

namespace Ziming\LaravelScrapingBee\Tests;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Ziming\LaravelScrapingBee\LaravelScrapingBeeShopee;

class LaravelScrapingBeeShopeeTest extends TestCase
{
    #[Test]
    public function it_sends_a_shopee_request_with_bearer_auth_and_supported_parameters(): void
    {
        config()->set('scrapingbee.api_key', 'test-api-key');
        config()->set('scrapingbee.shopee_base_url', 'https://example.test/shopee');

        Http::fake([
            '*' => Http::response(['title' => 'Example product']),
        ]);

        $productUrl = 'https://shopee.co.id/example-product-i.93014939.1881883105?extraParams=%7B%22model%22%3A1%7D';

        $response = LaravelScrapingBeeShopee::make()
            ->url($productUrl)
            ->addHtml()
            ->tag('catalog-import')
            ->get();

        $this->assertSame('Example product', $response->json('title'));

        Http::assertSent(function (Request $request) use ($productUrl): bool {
            return str_starts_with($request->url(), 'https://example.test/shopee?')
                && $request->hasHeader('Authorization', 'Bearer test-api-key')
                && $request->data() === [
                    'url' => $productUrl,
                    'add_html' => true,
                    'tag' => 'catalog-import',
                ];
        });
    }

    #[Test]
    public function it_supports_explicit_boolean_values_and_custom_parameters(): void
    {
        Http::fake([
            'https://app.scrapingbee.com/api/v1/shopee*' => Http::response([]),
        ]);

        LaravelScrapingBeeShopee::make('explicit-api-key')
            ->url('https://shopee.co.id/example-product-i.93014939.1881883105')
            ->addHtml(false)
            ->setParam('future_parameter', 'value')
            ->get();

        Http::assertSent(fn (Request $request): bool => $request->hasHeader('Authorization', 'Bearer explicit-api-key')
            && $request->data() === [
                'url' => 'https://shopee.co.id/example-product-i.93014939.1881883105',
                'add_html' => false,
                'future_parameter' => 'value',
            ]);
    }

    #[Test]
    public function it_uses_the_configured_timeout(): void
    {
        config()->set('scrapingbee.timeout', 45);

        Http::fake(function (Request $request, array $options) {
            $this->assertSame(45, $options['timeout']);

            return Http::response([]);
        });

        LaravelScrapingBeeShopee::make('test-api-key')
            ->url('https://shopee.co.id/example-product-i.93014939.1881883105')
            ->get();
    }

    #[Test]
    public function it_resets_parameters_after_each_request(): void
    {
        Http::fake([
            'https://app.scrapingbee.com/api/v1/shopee*' => Http::response([]),
        ]);

        $client = LaravelScrapingBeeShopee::make('test-api-key');

        $client->url('https://shopee.co.id/first-product-i.1.1')->addHtml()->tag('first')->get();
        $client->url('https://shopee.co.id/second-product-i.2.2')->get();

        Http::assertSent(function (Request $request): bool {
            $data = $request->data();

            return ($data['url'] ?? null) === 'https://shopee.co.id/second-product-i.2.2'
                && ! array_key_exists('add_html', $data)
                && ! array_key_exists('tag', $data);
        });
    }
}
