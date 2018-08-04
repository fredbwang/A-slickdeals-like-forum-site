<?php

namespace App\Filters;

use Illuminate\Http\Request;
use App\Channel;


class ThreadFilter extends Filter
{
    protected $filterBys = ['createBy', 'belongTo'];

    public function createBy($username)
    {
        $user = \App\User::where('name', $username)->firstOrFail();

        $this->queryBuilder->where('user_id', $user->id);

        return;
    }

    public function belongTo($channelSlug)
    {
        $channel = Channel::where('slug', $channelSlug)->firstOrFail();

        $this->queryBuilder->where('channel_id', $channel->id);

        return;
    }
}