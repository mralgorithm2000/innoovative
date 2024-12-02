<?php

namespace App\Jobs;

use App\Interfaces\NewsRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class FetchNewsFromAPI implements ShouldQueue
{
    use Queueable;

    /**
     * The parameters for the API request.
     *
     * @var array
     */
    protected $parameters;

    /**
     * The URL of the API endpoint.
     *
     * @var string
     */
    protected $url;


    /**
     * An instance of the NewsRepositoryInterface to interact with the news data repository.
     * 
     * This repository is used for saving the fetched news data.
     *
     * @var \App\Interfaces\NewsRepositoryInterface
     */
    protected $repositoryInstance;

    /**
     * Create a new job instance.
     *
     * @param array $parameters The parameters to be sent with the API request.
     * @param string $url The URL of the API to fetch data from.
     */
    public function __construct(array $parameters, string $url, NewsRepositoryInterface $repositoryInstance)
    {
        $this->parameters = $parameters;
        $this->url = $url;
        $this->repositoryInstance = $repositoryInstance;
    }

    /**
     * Execute the job.
     *
     * This method sends an HTTP GET request to the provided URL with the given parameters
     * to fetch the news data from the API.
     *
     * @return void
     */
    public function handle(): void
    {
        $response = Http::get($this->url, $this->parameters);

        $this->repositoryInstance->create($response->json());
    }
}
