<?php

return [
    'api_url' => env('EXCHANGE_RATE_API_URL'),

    'api_key' => env('EXCHANGE_RATE_API_KEY'),

    'base_currency' => env('BASE_CURRENCY'),

    'supported_currencies' => explode(
        ',',
        env('SUPPORTED_CURRENCIES', 'EUR,USD,GBP,RON,RUB')
    ),
];
