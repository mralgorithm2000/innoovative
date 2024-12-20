<?php

namespace App\Repositories;

use App\Interfaces\FetchedNewsRepositoryInterface;
use App\Models\News;
use Illuminate\Support\Collection;

class NewsAPIRepository implements FetchedNewsRepositoryInterface
{
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
