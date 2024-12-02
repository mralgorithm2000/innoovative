<?php

namespace App\Repositories;

use App\Interfaces\NewsRepositoryInterface;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class NewsRepository implements NewsRepositoryInterface
{

    protected $news;
    public function __construct(News $news)
    {
        $this->news = $news; 
    }

    public function all(Request $request): LengthAwarePaginator{
        return $this->news->filter($request)->paginate($request->pageSize);
    }

    public function sources(): LengthAwarePaginator{
        return $this->news->select(['source'])->distinct()->paginate(100);
    }
}