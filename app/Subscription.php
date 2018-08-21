<?php

namespace App;

use App\Reply;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\ThreadUpdated;

class Subscription extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * notify
     * 
     * Notify the user associated with this subscription
     *
     * @param App\Reply $reply
     * @return voids
     */
    public function notify(Reply $reply)
    {
        $this->user->notify(new ThreadUpdated($reply));
    }
}
