<?php

namespace App\Filters\News;


class Source
{
    public function filter($builder , $value)
    {
        return $builder->where('source','like',"%".$value."%");
    }
}
