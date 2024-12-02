<?php

namespace App\Repositories;

use App\Interfaces\FetchedNewsRepositoryInterface;
use App\Models\News;
use Illuminate\Support\Collection;

class TheGuardianRepository implements FetchedNewsRepositoryInterface
{
    public function all(): Collection
    {
        return News::all();
    }

    public function find(int $id): ?News
    {
        return News::find($id);
    }

    public function create(array $fetchedData): void
    {
        // Initialize an array to hold new news data
        $content = [];

        // Collect existing news URLs to avoid duplicate inserts
        $existingUrls = News::whereIn('url', collect($fetchedData['response']['results'])->pluck('webUrl'))->pluck('url')->toArray();

        // Loop through each article in the fetched data
        foreach ($fetchedData['response']['results'] as $data) {
            // Skip if the article already exists (i.e., the URL is already in the database)
            if (in_array($data['webUrl'], $existingUrls)) {
                continue;
            }

            // Prepare the content for insertion if the article does not already exist
            $content[] = [
                'title' => $data['webTitle'],
                'url' => $data['webUrl'],
                'source' => "The Guardian",
                'author' => $this->getAuthors($data['tags']),
            ];
        }

        // Insert all the collected new news records in a batch if there is any new content
        if (!empty($content)) {
            News::insert($content);
        }
    }

    public function update(int $id, array $data): bool
    {
        $news = $this->find($id);
        return $news ? $news->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $news = $this->find($id);
        return $news ? $news->delete() : false;
    }

    private function getAuthors(array $data): string{
        if(count($data) == 0){
            return "";
        }

        $Content = [];

        foreach($data as $d){
            $Content[] = $d['webTitle'];
        }

        return "By " . implode(" And " , $Content);
    }
}
