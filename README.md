# News Aggregator API

> A Laravel-based news aggregator that fetches news from external sources like NewsAPI, The Guardian, and The New York Times, stores it in a local database, and exposes a REST API for external use.

---

## Table of Contents

1. [Introduction](#introduction)
2. [Features](#features)
3. [Installation](#installation)
4. [Configuration](#configuration)
5. [Adding a New Service](#adding-a-new-service)
6. [Running the Fetch Command](#running-the-fetch-command)
7. [API Endpoints](#api-endpoints)
8. [License](#license)


---

## Introduction

This project is a **News Aggregator API** built using Laravel. The application fetches the latest news articles from multiple external news providers (NewsAPI, The Guardian, and The New York Times), stores the data in a local MySQL database, and provides a RESTful API to allow others to fetch aggregated news.

---

## Features

- **News Aggregation from Multiple Sources**: Aggregates news articles from several popular sources like NewsAPI, The Guardian, and The New York Times.
  
- **Easy to Add New Platforms**: The application is designed with extensibility in mind. You can easily integrate new news platforms without changing the core logic. By creating a new service class and updating the configuration, new platforms can be added with minimal code changes.

- **Database Storage**: News articles are fetched and stored in a local database, reducing the need for frequent API calls and enhancing performance.

- **RESTful API**: Exposes a public API that allows external applications to query aggregated news articles and filtered by source, author, etc.

- **Test Suite Included**: The project includes a set of tests for the API endpoints. You can easily run the tests to ensure everything works as expected.

- **Scheduled Data Fetching**: The application can automatically fetch news articles at scheduled intervals, reducing manual intervention.

- **Background Data Fetching Using Jobs**: The process of fetching news articles from external APIs is handled in the background using Laravel’s **job queue**. This means the application can perform data fetching asynchronously without blocking the main thread, allowing for efficient background processing. The job queue ensures that news is fetched in the background while the application remains responsive.

---

## Installation

Follow these steps to install and set up the News Aggregator API on your local machine.

1. **Clone the repository**:
   ```bash
   git clone https://github.com/mralgorithm2000/innoscripta.git
   ```

2. **Navigate to the project directory**:
    ```bash
    cd innoscripta
    ```

3. **Install the dependencies**:
    ```bash
    composer install
    ```
4. **Set up the environment file**:
    ```bash
    cp .env.example .env
    ```

5. **Generate an application key**
    ```bash
    php artisan key:generate
    ```
6. **Run Migrations**:
    To create the necessary database tables, run the Laravel migrations:
    ```bash
    php artisan migrate
    ```

## Configuration

To properly configure the News Aggregator API, you will need to modify the settings in the `.env` file for API keys and database credentials, and adjust the `config/news.php` file for configuring news services.

### News Service Configuration

The `config/news.php` file contains the configuration for the external news services like NewsAPI, The Guardian, and The New York Times. By default, the file includes configurations for these three services, but you can modify them or add new services as needed.

To configure or add new news sources, follow these steps:

- **Open `config/news.php`**:
   Navigate to the `config/news.php` file in your project directory. This file contains an array of all the configured news services.

   Below is the structure of the configuration for each news service:

   ```php
   [
       'sources' => [
           'news_api' => [
               'name' => 'NewsAPI',
               'api_key' => env('NEWSAPI_API_KEY', null),
               'parameters' => [
                   'q' => 'bitcoin', // This is a required parameter. Feel free to modify it!
                   'language' => ['en', 'ar'],
               ],
               'service' => App\Services\NewsAPIService::class,
               'enabled' => true,
           ],
           'the_guardian' => [
               'name' => 'The Guardian',
               'api_key' => env('THEGUARDIAN_API_KEY', null),
               'parameters' => [
                   'show-tags' => ['contributor'],
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
   ```

    Each news source has the following configuration options:

    - **name**: The name of the news service (e.g., NewsAPI, The Guardian, New York Times).
    - **api_key**: The API key for authenticating requests. This is fetched from your .env file. You should add your API keys there.
    - **parameters**: These are the query parameters that will be used when making the API requests. For example, q is a search term, and language specifies the languages for news articles.
    - **service**: The service class that is responsible for handling the fetching of news for this source. For example, App\Services\NewsAPIService::class.
    - **enabled**:  Set this to true to enable the news source. If you want to disable a source, set this to false.

- **Modify Existing Services(if needed!)**:
If you want to modify any of the existing services, such as changing the parameters or updating the service class, simply update the corresponding values within the sources array.



## Adding a New Service

To add a new news service to your News Aggregator, follow these steps. The process involves creating a service class, a repository to handle storing data, and adding your service to the configuration file. Here’s a detailed guide:

### 1. **Create a Service Class**

First, you need to create a service class inside the `app/Services` folder. The class should implement the `App\Interfaces\NewsSourceInterface`. This class will be responsible for fetching data from your news source.

Here’s an example of how to structure the service class:

#### Example: `ExampleNewsService.php`

```php
namespace App\Services;

use App\Interfaces\NewsSourceInterface;
use App\Jobs\FetchNewsFromAPI;
use App\Repositories\ExampleNewsRepository;

class ExampleNewsService implements NewsSourceInterface
{
    private $newsApiConfig;

    /**
     * Constructor that initializes the service with configuration settings from config/news.php.
     */
    public function __construct()
    {
        // Get the configuration settings for the news service from the config file
        $this->newsApiConfig = config('news.sources.example_news');
    }

    /**
     * Fetches news data from the Example News API.
     */
    public function fetch(): void
    {
        $this->validateArguments();

        $apiKey = $this->newsApiConfig['api_key'];
        
        // Get the parameters from the configuration and add the API key
        $parameters = $this->newsApiConfig['parameters'];
        $parameters['apiKey'] = $apiKey;

        // Define the URL for the Example News API endpoint
        $url = 'https://api.examplenews.com/v1/articles';

        // Dispatch a job to fetch news from the API in the background
        FetchNewsFromAPI::dispatch($parameters, $url, new ExampleNewsRepository());
    }

    /**
     * Validates the configuration arguments to ensure required parameters are present.
     */
    private function validateArguments()
    {
        if ($this->newsApiConfig == null || count($this->newsApiConfig) == 0) {
            throw new \InvalidArgumentException("ExampleNews: The configuration settings are missing or incomplete. Please ensure all required configurations are provided.");
        }

        $apiKey = @$this->newsApiConfig['api_key'];
        if (empty($apiKey)) {
            throw new \InvalidArgumentException("ExampleNews: The API key is missing or invalid. Please provide a valid API key.");
        }

        $qParameter = @$this->newsApiConfig['parameters']['q'];
        if (empty($qParameter)) {
            throw new \InvalidArgumentException("ExampleNews: The 'q' parameter is required and cannot be empty.");
        }
    }
}
```

2. **Create a Repository for Your Service**:
Now that you have the service class, you need to create a repository for your news service. The repository will be responsible for storing the fetched data in the database. Create a repository class that implements the App\Interfaces\FetchedNewsRepositoryInterface.

Here’s an example of how to implement the repository:

#### Example: `ExampleNewsRepository.php`

```php
namespace App\Repositories;

use App\Models\News;

class ExampleNewsRepository implements \App\Interfaces\FetchedNewsRepositoryInterface
{
    /**
     * Store the fetched news data in the database.
     *
     * @param array $fetchedData
     */
    public function create(array $fetchedData): void
    {
        // Initialize an array to hold new news data
        $content = [];

        // Collect existing news URLs to avoid duplicate inserts
        $existingUrls = News::whereIn('url', collect($fetchedData['articles'])->pluck('url'))->pluck('url')->toArray();

        // Loop through each article in the fetched data
        foreach ($fetchedData['articles'] as $data) {
            // Skip if the article already exists (i.e., the URL is already in the database)
            if (in_array($data['url'], $existingUrls)) {
                continue;
            }

            // Prepare the content for insertion if the article does not already exist
            $content[] = [
                'title' => $data['title'],
                'url' => $data['url'],
                'description' => $data['description'],
                'source' => $data['source']['name'],
                'author' => $data['author'],
            ];
        }

        // Insert all the collected new news records in a batch if there is any new content
        if (!empty($content)) {
            News::insert($content);
        }
    }
}
```

The create() function ensures that only unique articles (based on the URL) are stored in the database to prevent duplicates.

3. **Register Your Service in config/news.php**:

Once you've created your service and repository, you need to register the new service in the config/news.php file. This tells the application to use your new service when fetching news.

Add a new entry in the sources array within the config/news.php file, similar to how NewsAPI, The Guardian, and NYTimes are configured.

#### Example Configuration in: `config/news.php`

```php
return [
    'sources' => [
        // Existing services...
        'example_news' => [
            'name' => 'Example News',
            'api_key' => env('EXAMPLENEWS_API_KEY', null),
            'parameters' => [
                'q' => 'technology',  // Modify this query to suit your news service's parameters
                'language' => ['en'],
            ],
            'service' => App\Services\ExampleNewsService::class,  // Point to your new service class
            'enabled' => true,
        ],
    ],
];
```

Don't forget to add the corresponding API key to your .env file:

```env
EXAMPLENEWS_API_KEY=your_example_news_api_key_here
```

## Running the Fetch Command

To fetch the latest news from the APIs, you need to run the `php artisan fetch:news` command. This command will initiate the process of fetching news from all the enabled news services and storing the data in your database.

### 1. **Manually Running the Fetch Command**

You can trigger the fetch process manually by running the following Artisan command:

```bash
php artisan fetch:news
```

This will fetch the latest news from all active news sources (including any new services you've added) and store the data in your database.

### 2. **Running the Fetch Command Automatically with Cron**

To automate the fetching process, you can set up a cron job to run the php artisan schedule:run command at regular intervals. By default, this will trigger the fetch process every hour.

To set up a cron job on your server, add the following line to your crontab (run crontab -e to edit):

```bash
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```

This cron job will execute every minute, triggering all scheduled commands, including fetching news.

### 3. **Changing the Fetch Schedule**:
If you'd like to adjust how often the fetch command runs, you can modify the schedule in routes/console.php.

### 4. **Running the Queue Worker**:
Since the system uses Laravel's queue system to handle background tasks (like fetching data from APIs), you need to ensure that the queue worker is running. This worker processes jobs in the background, including fetching news.

To start the queue worker, run the following command:

```bash
php artisan queue:work
```

This command will continuously process any pending jobs, including those for fetching news from APIs. It's important to have the queue worker running at all times, especially if you're using cron jobs for automation.

## API Endpoints

The API for the news aggregator provides two primary endpoints:

1. **Get News**
2. **Get News Sources**

Both endpoints are **public** and **do not require authentication**.

### 1. **Get News**

The `GET /api/news` endpoint allows users to fetch aggregated news from various sources, with several optional query parameters to filter and customize the results.

#### **Endpoint:**

1. **Get News**:
```http
GET http://127.0.0.1:8000/api/news
```

**Query Parameters:**

| Parameter | Type | Description | Example |
|----------|----------|----------|----------|
| title | String | Filter news articles by a specific keyword or phrase in the title.| trump |
| source | String | Filter news articles by a specific news source (e.g., NewsAPI, The Guardian, New York Times, etc.). | Wired |
| author | String | Filter news articles by a specific author's name. | Joel Khalili |
| pageSize | Integer | Specify the number of results per page (default: 25, max: 100). | 10 |
| page | Integer | Specify the page number for pagination (default: 1). | 1 |

2. **Get News Sources**:

The GET /api/sources endpoint returns a list of available news sources from which news can be aggregated. You can use this endpoint to discover which sources are available for news aggregation.
Endpoint:

```http
GET http://127.0.0.1:8000/api/sources?page=1
```

**Query Parameters:**

| Parameter | Type | Description | Example |
|----------|----------|----------|----------|
| page | Integer | Specify the page number for pagination (default: 1). This endpoint returns 100 records per page.| 1 |




## License

This project is open-source and is licensed under the **MIT License**.

The **MIT License** is a permissive license that allows for the free use, modification, and distribution of this software, with minimal restrictions. This means you are free to:

- Use the software for personal or commercial purposes
- Modify the software to suit your needs
- Distribute the software or your modifications to others
- Include the software in your own projects

However, the software is provided "as is," without any warranty of any kind. This means the authors are not responsible for any issues, damages, or liabilities that may arise from using the software.