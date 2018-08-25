<?php

namespace App\Listeners;

use App\User;
use App\Events\ReplyCreated;
use App\Notifications\WereMentioned;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyMentionedUsers
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ReplyCreated  $event
     * @return void
     */
    public function handle(ReplyCreated $event)
    {
        $mentionedUsers = $event->reply->mentionedUsers();

        foreach($mentionedUsers as $name) {
            if ($user = User::whereName($name)->first()) {
                $user->notify(new WereMentioned($event->reply));
            }
        }

        // collect($event->reply->mentionedUsers())
        //     ->map(function ($name) {
        //         return User::whereName($name)->first();
        //     })
        //     ->filter()
        //     ->each(function ($user) use ($event) {
        //         $user->notify(new WereMentioned($event->reply));
        //     });
    }
}
