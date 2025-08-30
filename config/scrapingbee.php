<?php

declare(strict_types=1);

return [
    'api_key' => env('SCRAPINGBEE_API_KEY'),
    'base_url' => env('SCRAPINGBEE_BASE_URL', 'https://app.scrapingbee.com/api/v1/'),
    'timeout' => env('SCRAPINGBEE_TIMEOUT', 140),
    'google_search_base_url' => env('SCRAPINGBEE_GOOGLE_SEARCH_BASE_URL', 'https://app.scrapingbee.com/api/v1/store/google'),
    'walmart_search_base_url' => env('SCRAPINGBEE_WALMART_SEARCH_BASE_URL', 'https://app.scrapingbee.com/api/v1/walmart/search'),
];
