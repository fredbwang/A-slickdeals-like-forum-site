<?php

namespace App;

use Illuminate\Support\Facades\Redis;


class Visits
{
    protected $object;

    public function __construct($object)
    {
        $this->object = $object;
    }

    public function record()
    {
        Redis::incr($this->CacheKey());

        return $this;
    }

    public function count()
    {
        return Redis::get($this->CacheKey()) ?? 0;
    }

    public function reset()
    {
        Redis::del($this->CacheKey());

        return $this;
    }

    public function CacheKey()
    {
        $objectType = get_class($this->object);

        return "{$objectType}.{$this->object->id}.visits";
    }
}