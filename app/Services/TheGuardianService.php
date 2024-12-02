<?php

namespace App\Services;

use App\Interfaces\NewsSourceInterface;
use App\Jobs\FetchNewsFromAPI;
use App\Repositories\TheGuardianRepository;
use Illuminate\Support\Facades\Http;

class TheGuardianService implements NewsSourceInterface
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
        $this->newsApiConfig = config('news.sources.the_guardian');
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

        // Define the URL for The Guardian API endpoint
        $parameters = $this->createParameters();
        $parameters['api-key'] = $apiKey;
        $url = 'https://content.guardianapis.com/search';
        $response = Http::get($url, $parameters);

        // Dispatch a job to fetch news from the API using the specified parameters, URL, and repository
        FetchNewsFromAPI::dispatch($parameters, $url, new TheGuardianRepository());
    }

    /**
     * Validates the configuration arguments.
     *
     *
     * @throws \InvalidArgumentException If any required configuration is missing or invalid.
     */
    private function validateArguments()
    {
        $parameters = $this->newsApiConfig['parameters'];
        $apiKey = @$this->newsApiConfig['api_key'];

        if ($this->newsApiConfig == null || count($this->newsApiConfig) == 0) {
            throw new \InvalidArgumentException("The Guardian: The configuration settings are missing or incomplete. Please ensure all required configurations are provided to proceed.");
        }

        if ($apiKey == '') {
            throw new \InvalidArgumentException("The Guardian: The API key is missing or invalid. Please provide a valid API key to proceed.");
        }

        if (@$parameters['format'] != '' && @$parameters['format'] != 'json') {
            throw new \InvalidArgumentException("The Guardian: Only 'json' format is supported. Please ensure the format is set to 'json' to proceed.");
        }

        if (@$parameters['callback'] != '') {
            throw new \InvalidArgumentException("The Guardian: The 'callback' parameter is not supported. Please remove it to proceed.");
        }

        if (!in_array('contributor',$parameters['show-tags'])) {
            throw new \InvalidArgumentException("The Guardian: The 'show-tags.contributor' parameter is required and cannot be empty. Please provide a valid value to proceed.");
        }
    }

    /**
     * Converts nested array parameters into a flattened format.
     *
     *
     * @return array The transformed parameters array.
     */

    private function createParameters()
    {
        $parameters = $this->newsApiConfig['parameters'];
        $Content = [];
        foreach ($parameters as $key => $value) {
            if (is_array($value)) {
                $Content[$key] = implode(",", $value);
            } else {
                $Content[$key] = $value;
            }
        }
        return $Content;
    }
}
