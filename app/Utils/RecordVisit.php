<?php

namespace App\Utils;

use Illuminate\Support\Facades\Redis;
use App\Visits;


trait RecordVisit
{
    public function visits()
    {
        return new Visits($this);
    }

}