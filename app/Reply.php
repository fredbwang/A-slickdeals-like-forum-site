<?php

namespace App;

use App\Utils\Votable;
use Illuminate\Database\Eloquent\Model;
use App\Utils\RecordActivity;

class Reply extends Model
{
    use Votable;
    use RecordActivity;

    protected $guarded = [];

    protected $with = ['owner', 'votes', 'thread'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class, 'thread_id');
    }
}
