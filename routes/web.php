<?php

use App\Services\NewsAPIService;
use App\Services\NewsSourceContext;
use App\Services\NYTimesService;
use App\Services\TheGuardianService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/NewsAPI', function () {
    $newsSource = new NewsSourceContext();
    
    $newsSource->setNewsSource(new NewsAPIService());
    return $newsSource->fetch();
});

Route::get('/NYTimes', function () {
    $newsSource = new NewsSourceContext();
    
    $newsSource->setNewsSource(new NYTimesService());
    return $newsSource->fetch();
});

Route::get('/TheGuardian', function () {
    $newsSource = new NewsSourceContext();
    
    $newsSource->setNewsSource(new TheGuardianService());
    return $newsSource->fetch();
});

