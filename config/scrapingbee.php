<?php

declare(strict_types=1);

return [
    'api_key' => env('SCRAPINGBEE_API_KEY'),
    'base_url' => env('SCRAPINGBEE_BASE_URL', 'https://app.scrapingbee.com/api/v1/'),
    'timeout' => env('SCRAPINGBEE_TIMEOUT', 140),

    'google_search_base_url' => env('SCRAPINGBEE_GOOGLE_SEARCH_BASE_URL', 'https://app.scrapingbee.com/api/v1/store/google'),

    'walmart_search_base_url' => env('SCRAPINGBEE_WALMART_SEARCH_BASE_URL', 'https://app.scrapingbee.com/api/v1/walmart/search'),
    'walmart_product_base_url' => env('SCRAPINGBEE_WALMART_PRODUCT_BASE_URL', 'https://app.scrapingbee.com/api/v1/walmart/product'),

    'amazon_search_base_url' => env('SCRAPINGBEE_AMAZON_SEARCH_BASE_URL', 'https://app.scrapingbee.com/api/v1/amazon/search'),
    'amazon_product_base_url' => env('SCRAPINGBEE_AMAZON_PRODUCT_BASE_URL', 'https://app.scrapingbee.com/api/v1/amazon/product'),

    'youtube_search_base_url' => env('SCRAPINGBEE_YOUTUBE_SEARCH_BASE_URL', 'https://app.scrapingbee.com/api/v1/youtube/search'),
    'youtube_metadata_base_url' => env('SCRAPINGBEE_YOUTUBE_METADATA_BASE_URL', 'https://app.scrapingbee.com/api/v1/youtube/metadata'),
    'youtube_transcript_base_url' => env('SCRAPINGBEE_YOUTUBE_TRANSCRIPT_BASE_URL', 'https://app.scrapingbee.com/api/v1/youtube/transcript'),

    // Check video transcript availability
    'youtube_trainability_base_url' => env('SCRAPINGBEE_YOUTUBE_TRAINABILITY_BASE_URL', 'https://app.scrapingbee.com/api/v1/youtube/trainability'),


    'chatgpt_base_url' => env('SCRAPINGBEE_CHATGPT_BASE_URL', 'https://app.scrapingbee.com/api/v1/chatgpt'),
];
