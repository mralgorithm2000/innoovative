<?php

namespace App\Interfaces;

interface NewsSourceInterface
{
    /**
     * Fetch news from the source.
     *
     * @return array The fetched news articles.
     */
    public function fetch(): void;
}