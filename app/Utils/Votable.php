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
     * @return array
     */
    public function vote(int $score)
    {
        // using laravel's auto morph fill in
        if ($this->votes()->where(['user_id' => auth()->id()])->exists()) {
            $this->votes()->where(['user_id' => auth()->id()])->first()->update(['score' => $score]);
            return ['action' => 'update'];
        } else {
            $this->votes()->create(['user_id' => auth()->id(), 'score' => $score]);
            return ['action' => 'create'];
        }

        // Vote::create([
        //     'user_id' => auth()->id(),
        //     'voted_id' => $reply->id,
        //     'voted_type' => get_class($reply),
        //     'score' => $score
        // ]);
    }

    public function getUpVotesCountAttribute()
    {
        return $this->votes->where('score', 1)->count();
    }

    public function getDownVotesCountAttribute()
    {
        return $this->votes->where('score', -1)->count();
    }

    public function getCurrentVoteAttribute()
    {
        return $this->votes->where('user_id', auth()->id())->sum('score');
    }

    public function isUpVote()
    {
        return $this->getCurrentVoteAttribute() > 0;
    }

    public function isDownVote()
    {
        return $this->getCurrentVoteAttribute() < 0;
    }

    /**
     * votePath
     * Get the path where vote request post to
     * @param String $action
     * @return String
     */
    public function votePath($action = "vote-up")
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());
        $type_plural = str_plural($type, 2);
        return "/{$type_plural}/{$this->id}/vote/{$action}";
    }
}