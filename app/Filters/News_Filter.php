<?php

namespace App\Filters;

use App\Filters\AbstractFilter;
use App\Filters\News\Title;
use App\Filters\News\Source;
use App\Filters\News\Author;
use Illuminate\Database\Eloquent\Builder;

class News_Filter extends AbstractFilter
{
   

    protected $filters = [
        'title'                         => Title::class,
        'source'                        => Source::class,
        'author'                        => Author::class
    ];
}
