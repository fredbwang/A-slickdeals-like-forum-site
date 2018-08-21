<?php

namespace App;

use App\Utils\Votable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Utils\RecordActivity;
use App\Notifications\ThreadUpdated;

class Thread extends Model
{
    use RecordActivity;

    use Votable;

    protected $guarded = [];

    protected $with = ['owner', 'votes', 'channel'];

    protected $appends = ['isSubscribedTo'];

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
            $thread->subscriptions->each->delete();
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
                'votes as upVotesCount' => function ($query) {
                    $query->where('score', 1);
                },
                'votes as downVotesCount' => function ($query) {
                    $query->where('score', -1);
                },
                'votes as currentVote' => function ($query) {
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
        $reply = $this->replies()->create($reply);

        // prepare notifications for subscribing users
        $this->subscriptions
            ->filter(function ($subject) use ($reply) {
                return $subject->user_id != $reply->user_id;
            })
            ->each->notify($reply);

        return $reply;
    }

    public function scopeFilterWith($query, $filters)
    {
        return $filters->apply($query);
    }

    /**
     * subscribedBy
     *
     * @param int $userId
     * @return $this
     */
    public function subscribedBy($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ? : auth()->id(),
        ]);

        return $this;
    }

    public function unsubscribedFrom($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ? : auth()->id())
            ->delete();
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function getIsSubscribedToAttribute($userId = null)
    {
        return $this->subscriptions()
            ->where('user_id', $userId ? : auth()->id())
            ->exists();
    }
}
