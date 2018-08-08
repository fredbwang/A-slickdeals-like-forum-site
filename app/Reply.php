<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $guarded = [];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function votes()
    {
        return $this->morphMany(Vote::class, 'voted');
    }

    /**
     * vote
     * vote up or down this comment (aka reply) with a score
     * @param int $score
     * @return void
     */
    public function vote(int $score)
    {
        // using laravel's auto morph fill in
        if (!$this->votes()->where(['user_id' => auth()->id()])->exists()) {
            $this->votes()->create(['user_id' => auth()->id(), 'score' => $score]);
        } else {
            $this->votes()->where(['user_id' => auth()->id()])->update(['score' => $score]);
        }

        // Vote::create([
        //     'user_id' => auth()->id(),
        //     'voted_id' => $reply->id,
        //     'voted_type' => get_class($reply),
        //     'score' => $score
        // ]);
    }

    public function upVotesNum()
    {
        return $this->votes()->where('score', 1)->count();
    }

    public function downVotesNum()
    {
        return $this->votes()->where('score', -1)->count();
    }

    public function isUpVote()
    {
        return $this->votes()
            ->where([
                ['user_id', '=', auth()->id()],
                ['score', '=', 1]
            ])
            ->exists();
    }

    public function isDownVote()
    {
        return $this->votes()
            ->where([
                ['user_id', '=', auth()->id()],
                ['score', '=', -1]
            ])
            ->exists();
    }
}
