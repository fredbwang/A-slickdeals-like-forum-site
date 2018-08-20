<?php

namespace App\Filters;

use Illuminate\Http\Request;
use App\Channel;


class ThreadFilter extends Filter
{
    protected $filterBys = ['createBy', 'belongTo', 'popular', 'uncommented'];

    /**
     * createBy
     * Filter by which user created the thread
     * @param String $username
     * @return void
     */
    public function createBy($username)
    {
        $user = \App\User::where('name', $username)->firstOrFail();

        $this->queryBuilder->where('user_id', $user->id);

        return;
    }

    /**
     * belongTo
     * Filter by which channel the thread belongs to
     * @param String $channelSlug
     * @return void
     */
    public function belongTo($channelSlug)
    {
        $channel = Channel::where('slug', $channelSlug)->firstOrFail();

        $this->queryBuilder->where('channel_id', $channel->id);

        return;
    }

    /**
     * popular
     * Filter threads by popularity in desc order
     * @return void
     */
    public function popular()
    {
        // empty the query order
        // $this->queryBuilder->getQuery()->orders = []
        
        $this->queryBuilder->orderBy('replies_count', 'desc');
        return;
    }

    /**
     * uncommented
     * Filter threads by answered or not
     *
     * @return void
     */
    public function uncommented() {
        $this->queryBuilder->having('replies_count', 0);
        return;
    }
}