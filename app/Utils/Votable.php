<?php

namespace App\Utils;

use App\Vote;

trait Votable
{
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
        if ($this->votes()->where(['user_id' => auth()->id()])->exists()) {
            $this->votes()->where(['user_id' => auth()->id()])->first()->update(['score' => $score]);
        } else {
            $this->votes()->create(['user_id' => auth()->id(), 'score' => $score]);
        }

        // Vote::create([
        //     'user_id' => auth()->id(),
        //     'voted_id' => $reply->id,
        //     'voted_type' => get_class($reply),
        //     'score' => $score
        // ]);
    }

    public function upVotesCount()
    {
        return $this->votes->where('score', 1)->count();
    }

    public function downVotesCount()
    {
        return $this->votes->where('score', -1)->count();
    }

    public function isUpVote()
    {
        return !!$this->votes
            ->where('user_id', auth()->id())
            ->where('score', 1)
            ->count();
    }

    public function isDownVote()
    {
        return !!$this->votes
            ->where('user_id', auth()->id())
            ->where('score', -1)
            ->count();
    }
}