<?php

namespace App;

use App\Utils\Votable;
use App\Utils\RecordActivity;
use App\Utils\RecordVisit;
use App\Notifications\ThreadUpdated;
use App\Events\ReplyCreated;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Thread extends Model
{
    use RecordActivity, RecordVisit;

    use Votable, Searchable;

    protected $guarded = [];

    protected $with = ['owner', 'votes', 'channel'];

    protected $appends = ['isSubscribedBy'];

    protected $casts = ['locked' => 'boolean'];

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

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function path($suffix = null)
    {
        return "/threads/{$this->channel->slug}/{$this->slug}{$suffix}";
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

        event(new ReplyCreated($reply));

        return $reply;
    }

    public function notifySubscribers($reply)
    {
        $this->subscriptions
            ->where('user_id', '!=', $reply->user_id)
            ->each->notify($reply);
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
        return $this;
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function getIsSubscribedByAttribute($userId = null)
    {
        return $this->subscriptions()
            ->where('user_id', $userId ? : auth()->id())
            ->exists();
    }

    public function getVisitsCountAttribute()
    {
        return $this->visits()->count();
    }

    public function setSlugAttribute($value)
    {
        if (static::whereSlug($slug = str_slug($value))->exists()) {
            $slug = $this->incrementSlug($slug);
        }

        $this->attributes['slug'] = $slug;
    }

    public function incrementSlug($slug, $count = 1)
    {
        $original = $slug;

        while (static::whereSlug($slug)->exists()) {
            $slug = "{$original}-" . $count++;
        }

        return $slug;
    }

    public function markBestReply(Reply $reply)
    {
        $this->update(['best_reply_id' => $reply->id]);
    }

    /**
     * Lock the thread
     *
     * @return void
     */
    public function lock()
    {
        $this->update(['locked' => true]);
    }

    /**
     * Unlock the thread
     *
     * @return void
     */
    public function unlock()
    {
        $this->update(['locked' => false]);
    }

    public function toSearchableArray()
    {
        return $this->toArray() + ['path' => $this->path()];
    }
}
