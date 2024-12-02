<?php

namespace App\Services;

use App\Interfaces\NewsSourceInterface;
use App\Jobs\FetchNewsFromAPI;
use App\Repositories\NewsAPIRepository;
use Illuminate\Support\Facades\Http;

class NewsAPIService implements NewsSourceInterface
{
    /**
     * Configuration settings for NewsAPI.
     *
     * @var array|null
     */
    private $newsApiConfig;

     /**
     * NewsAPIService constructor.
     * Initializes the service with configuration settings from the news config file.
     */
    public function __construct()
    {
        $this->newsApiConfig = config('news.sources.news_api');
    }

     /**
     * Fetches news data from NewsAPI.
     *
     *
     * @return array The JSON response data from the NewsAPI.
     *
     * @throws \InvalidArgumentException If configuration settings are missing or invalid.
     */
    public function fetch(): void
    {
        $this->validateArguments();

        $apiKey = $this->newsApiConfig['api_key'];
        
        // Get the parameters from the configuration and add the API key to them
        $parameters = $this->newsApiConfig['parameters'];
        $parameters['apiKey'] = $apiKey;

        // Define the URL for the News API endpoint
        $url = 'https://newsapi.org/v2/everything';

        // Dispatch a job to fetch news from the API using the specified parameters, URL, and repository
        FetchNewsFromAPI::dispatch($parameters,$url,new NewsAPIRepository());
    }

    /**
     * Validates the configuration arguments.
     *
     *
     * @throws \InvalidArgumentException If any required configuration is missing or invalid.
     */
    private function validateArguments()
    {
        // Check if the configuration is missing or empty
        if ($this->newsApiConfig == null || count($this->newsApiConfig) == 0) {
            throw new \InvalidArgumentException("NewsAPI: The configuration settings are missing or incomplete. Please ensure all required configurations are provided to proceed.");
        }

        // Check if the API key is missing
        $apiKey = @$this->newsApiConfig['api_key'];
        if ($apiKey == '') {
            throw new \InvalidArgumentException("NewsAPI: The API key is missing or invalid. Please provide a valid API key to proceed.");
        }

        // Check if the 'q' parameter is missing or empty
        $qParameter = @$this->newsApiConfig['parameters']['q'];
        if (empty($qParameter)) {
            throw new \InvalidArgumentException("NewsAPI: The 'q' parameter is required and cannot be empty. Please provide a valid 'q' parameter to proceed.");
        }
    }
}
