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

    protected $with = ['owner', 'votes'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
