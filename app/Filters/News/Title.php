<?php

namespace App\Filters\News;


class Title
{
    public function filter($builder , $value)
    {
        return $builder->where('title','like',"%".$value."%");
    }
}
