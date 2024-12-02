<?php

namespace App\Models;

use App\Filters\News_Filter;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    public function scopeFilter(Builder $builder,$request){
        return (new News_Filter($request))->filter($builder);
    }
    
    protected $table = 'news';

    protected $fillable = [
        'title',
        'url',
        'source',
        'description',
        'author'
    ];
}
