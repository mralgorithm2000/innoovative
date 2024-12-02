<?php

namespace App\Services;

use App\Interfaces\NewsSourceInterface;

class NewsSourceContext
{
    private NewsSourceInterface $NewsSource;

    public function setNewsSource(NewsSourceInterface $NewsSource): void
    {
        $this->NewsSource = $NewsSource;
    }

    public function fetch(): void
    {
        $this->NewsSource->fetch();
    }
}