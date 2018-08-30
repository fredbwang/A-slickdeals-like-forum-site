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
        if (!$this->wasJustVisited()) {
            Redis::incr($this->cacheKey());
        }

        return $this;
    }

    public function count()
    {
        return Redis::get($this->cacheKey()) ?? 0;
    }

    public function reset()
    {
        Redis::del($this->cacheKey());

        return $this;
    }

    public function wasJustVisited()
    {
        $visitedKey = $this->cacheKey() . session()->getId();

        if (Redis::get($visitedKey) != null) {
            Redis::setEx($visitedKey, 10, true);
            return true;
        } else {
            Redis::setEx($visitedKey, 10, true);
            return false;
        }
    }

    public function cacheKey()
    {
        $objectType = get_class($this->object);

        return "{$objectType}.{$this->object->id}.visits";
    }
}