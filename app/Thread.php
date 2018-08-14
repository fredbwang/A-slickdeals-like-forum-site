<?php

namespace App;

use App\Utils\Votable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Utils\RecordActivity;

class Thread extends Model
{
    use RecordActivity;

    use Votable;

    protected $guarded = [];

    protected $with = ['owner', 'votes', 'channel'];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('replyCount', function ($builder) {
            $builder->withCount('replies');
        });

        static::deleting(function ($thread) {
            // normal way to delete each reply
            // $thread->replies->each(function($reply) {
            //     $reply->delete();
            // });

            // laravel higher order collection proxy
            $thread->replies->each->delete();
        });
    }

    public function path($suffix = null)
    {
        return "/threads/{$this->channel->slug}/{$this->id}{$suffix}";
    }

    public function replies()
    {
        return $this->hasMany(Reply::class)
            ->withCount([
                'votes as up_votes_count' => function ($query) {
                    $query->where('score', 1);
                },
                'votes as down_votes_count' => function ($query) {
                    $query->where('score', -1);
                },
                'votes as current_vote' => function ($query) {
                    $query->where('user_id', auth()->id());
                }
            ])
            ->with('owner');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class, 'channel_id');
    }

    public function addReply($reply)
    {
        $this->replies()->create($reply);
    }

    public function scopeFilterWith($query, $filters)
    {
        return $filters->apply($query);
    }
}
