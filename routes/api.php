<?php

use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\Route;

Route::get('news',[ApiController::class,'getNews']);
Route::get('sources',[ApiController::class,'getSources']);
