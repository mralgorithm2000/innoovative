<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\NewsRepository;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    protected $NewsRepository;
    public function __construct(NewsRepository $NewsRepository)
    {
        $this->NewsRepository = $NewsRepository;
    }

    public function getNews(Request $request)
    {
        $request->validate([
            'title'   => 'nullable|string|max:255',
            'source'  => 'nullable|string|max:255',
            'author'  => 'nullable|string|max:255',
            'pageSize' => 'nullable|integer|min:1|max:100',
        ]);

        $Content = $this->NewsRepository->all($request);

        return response()->json([
            'error' => false,
            'Content' => $Content,
        ]);
    }

    public function getSources(){
        $Content = $this->NewsRepository->sources();

        return response()->json([
            'error' => false,
            'Content' => $Content,
        ]);
    }
}
