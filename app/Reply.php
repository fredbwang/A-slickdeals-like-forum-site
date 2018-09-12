<?php

namespace App;

use App\Utils\Votable;
use Illuminate\Database\Eloquent\Model;
use App\Utils\RecordActivity;
use Laravel\Scout\Searchable;

class Reply extends Model
{
    use RecordActivity;

    use Votable, Searchable;

    const MENTIONED_USER_NAME_PATTERN = '/(?<=^|(?<=[^a-zA-Z0-9-_\.]))@([A-Za-z\.\_]+[A-Za-z0-9\-\_]+)/';

    protected $guarded = [];

    protected $with = ['owner', 'votes', 'thread'];

    protected $appends = ['upVotesCount', 'downVotesCount', 'currentVote', 'isBest'];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($reply) {
            if ($reply->isMarked('best')) {
                $reply->thread->update(['best_reply_id' => null]);
            }

            $reply->votes->each->delete();
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class, 'thread_id');
    }

    public function path()
    {
        return $this->thread->path() . "#reply-{$this->id}";
    }

    public function wasJustCreated()
    {
        return $this->created_at->gt(now()->subSeconds(10));
    }

    public function mentionedUsers()
    {
        preg_match_all(self::MENTIONED_USER_NAME_PATTERN, $this->body, $matches);

        return $matches[1];
    }

    public function setBodyAttribute($body)
    {
        $this->attributes['body'] = preg_replace(self::MENTIONED_USER_NAME_PATTERN, '<a href="/profiles/$1">$0</a>', $body);
    }

    public function isMarked($tag = "best")
    {
        if ($tag == 'best') {
            return $this->thread->best_reply_id == $this->id;
        } else if ($tag == 'helpful') {
            return !!$this->is_helpful;
        }
    }

    public function getIsBestAttribute()
    {
        return $this->isMarked('best');
    }

    // public function getIsHelpfulAttribute()
    // {
    //     return $this->isMarked('helpful');
    // }
}
