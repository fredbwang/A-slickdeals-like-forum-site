<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Reply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Exception;

class ReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($channel, Thread $thread)
    {
        return $thread->replies()->paginate(8);
    }

    /**
     * Persist a new Reply
     *
     * @param int $channelId
     * @param App\Thread $thread
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($channelId, Thread $thread)
    {
        if (Gate::denies('create', new Reply)) {
            return response('You are commenting too frequently.', 429);
        }

        try {
            request()->validate(['body' => 'required|spamfree']);

            $reply = $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id()
            ]);
        } catch (Exception $e) {
            return response('Your comment is invalid!', 422);
        }

        return $reply->load('owner');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reply  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('delete', $reply);

        $reply->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Reply deleted'], 202);
        }

        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Reply $reply
     * @return \Illuminate\Http\Response
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        try {
            request()->validate(['body' => 'required|spamfree']);
        } catch (Exception $e) {
            return response('Your comment is invalid!', 422);
        }

        $reply->update(['body' => request('body')]);
    }
}
