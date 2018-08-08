<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Thread extends Model
{
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('replyCount', function ($builder) {
            $builder->withCount('replies');
        });
    }

    public function path($suffix = null)
    {
        return "/threads/{$this->channel->slug}/{$this->id}{$suffix}";
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class, 'channel_id');
    }

    public function votes()
    {
        return $this->morphMany(Vote::class, 'voted');
    }

    /**
     * vote
     * vote up or down this thread (aka deal) with a score
     * @param int $score
     * @return void
     */
    public function vote(int $score)
    {
        if (!$this->votes()->where(['user_id' => auth()->id()])->exists()) {
            $this->votes()->create(['user_id' => auth()->id(), 'score' => $score]);
        }
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
