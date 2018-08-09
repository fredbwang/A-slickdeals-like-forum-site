<?php

namespace App;

use App\Utils\Votable;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Votable;
    
    protected $guarded = [];

    protected $with = ['owner', 'votes'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
