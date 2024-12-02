<?php

namespace App\Services;

use App\Interfaces\NewsSourceInterface;
use App\Jobs\FetchNewsFromAPI;
use App\Repositories\NYTimesRepository;
use Illuminate\Support\Facades\Http;

class NYTimesService implements NewsSourceInterface
{
   /**
     * Configuration settings for The Guardian.
     *
     * @var array|null
     */
    private $newsApiConfig;


    /**
     * TheGuardianService constructor.
     * Initializes the service with configuration settings from the news config file.
     */
    public function __construct()
    {
        $this->newsApiConfig = config('news.sources.nytimes');
    }

    /**
     * Fetches news data from The Guardian.
     *
     *
     * @return array The JSON response data from the The Guardian.
     *
     * @throws \InvalidArgumentException If configuration settings are missing or invalid.
     */
    public function fetch(): void
    {
        $this->validateArguments();

        // Get the parameters from the configuration and add the API key to them
        $apiKey = $this->newsApiConfig['api_key'];
        $parameters['api-key'] = $apiKey;

        // Define the URL for the New York Times API endpoint
        $url = 'https://api.nytimes.com/svc/news/v3/content/all/all.json';

        // Dispatch a job to fetch news from the API using the specified parameters, URL, and repository
        FetchNewsFromAPI::dispatch($parameters,$url,new NYTimesRepository());
    }

    /**
     * Validates the configuration arguments.
     *
     *
     * @throws \InvalidArgumentException If any required configuration is missing or invalid.
     */
    private function validateArguments()
    {
        $apiKey = @$this->newsApiConfig['api_key'];

        if ($this->newsApiConfig == null || count($this->newsApiConfig) == 0) {
            throw new \InvalidArgumentException("New York Times: The configuration settings are missing or incomplete. Please ensure all required configurations are provided to proceed.");
        }

        if ($apiKey == '') {
            throw new \InvalidArgumentException("New York Times: The API key is missing or invalid. Please provide a valid API key to proceed.");
        }
    }
}