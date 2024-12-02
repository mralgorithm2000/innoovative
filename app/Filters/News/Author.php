<?php

namespace App\Filters\News;


class Author
{
    public function filter($builder , $value)
    {
        return $builder->where('author','like',"%".$value."%");
    }
}
