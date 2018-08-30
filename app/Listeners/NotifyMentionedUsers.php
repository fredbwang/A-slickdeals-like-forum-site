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
        // foreach ($event->reply->mentionedUsers() as $name) {
        //     if ($user = User::whereName($name)->first()) {
        //         $user->notify(new WereMentioned($event->reply));
        //     }
        // }

        User::whereIn('name', $event->reply->mentionedUsers())
            ->get()
            ->each(function ($user) use ($event) {
                $user->notify(new WereMentioned($event->reply));
            });
    }
}
