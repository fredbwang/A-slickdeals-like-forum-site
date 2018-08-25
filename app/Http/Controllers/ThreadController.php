<?php

namespace App\Http\Controllers;

use \Exception;
use App\Thread;
use App\Channel;
use App\Filters\ThreadFilter;
use Illuminate\Http\Request;
use Illuminate\Foundation\Console\Presets\React;

class ThreadController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, Request $request)
    {
        $threads = $this->getThreads($channel, new ThreadFilter($request));

        if ($request->expectsJson()) {
            return $threads;
        }

        return view('threads.index', compact('threads', $channel->exists ? 'channel' : null));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'title' => 'required|spamfree',
            'body' => 'required|spamfree',
            'channel_id' => 'required|exists:channels,id'
        ]);

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => request('channel_id'),
            'title' => request('title'),
            'body' => request('body')
        ]);

        return redirect($thread->path())
            ->with('flash', [
                'message' => 'Your deal has been posted!',
                'type' => 'success'
            ]);
    }

    /**
     * Display the specified resource.
     * @param int $channelId
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show(Channel $channel, Thread $thread)
    {
        return view('threads.show', compact('thread'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy(Channel $channel, Thread $thread)
    {
        $this->authorize('delete', $thread);

        $thread->delete();
        return redirect('/threads');
    }

    public function getThreads(Channel $channel, ThreadFilter $filter)
    {
        $threads = Thread::filterWith($filter);
        if ($channel->exists) {
            $threads = $threads->where('channel_id', $channel->id);
        }

        return $threads->latest()->get();
    }
}
