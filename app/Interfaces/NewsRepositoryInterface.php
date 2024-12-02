<?php

namespace App\Interfaces;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface NewsRepositoryInterface
{
    public function all(Request $reuqest): LengthAwarePaginator;
    public function sources(): LengthAwarePaginator;
}