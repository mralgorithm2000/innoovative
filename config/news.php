<?php

return [
    'sources' => [
        'news_api' => [
            'name' => 'NewsAPI',
            'api_key' => env('NEWSAPI_API_KEY', null),
            'parameters' => [
                'q' => 'bitcoin', //This is a required parameter. Please do not remove it; however, feel free to modify it!
                'language' => ['en','ar']
                // Check for other parameters here https://newsapi.org/docs/endpoints/everything; you might want to add additional parameters to this list.
            ],
            'service' => App\Services\NewsAPIService::class,
            'enabled' => true,
        ],
        'the_guardian' => [
            'name' => 'The Guardian',
            'api_key' => env('THEGUARDIAN_API_KEY', null),
            'parameters' => [
                'show-tags' => [
                    'contributor'
                ]
                // Check for available parameters https://open-platform.theguardian.com/documentation/search
            ],
            'service' => App\Services\TheGuardianService::class,
            'enabled' => true,
        ],
        'nytimes' => [
            'name' => 'New York Times',
            'api_key' => env('NYTIMES_API_KEY', null),
            'service' => App\Services\NYTimesService::class,
            'enabled' => true,
        ],
    ],
];
