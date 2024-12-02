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
        $pageSize = ($request->pageSize == '') ? 25 : $request->pageSize;
        return $this->news->filter($request)->paginate($pageSize);
    }

    public function sources(): LengthAwarePaginator{
        return $this->news->select(['source'])->distinct()->paginate(100);
    }
}