<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MentionUserTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */
    public function mentioned_user_in_a_reply_is_notified()
    {
        $user_1 = create('App\User', ['name' => 'user_1']);

        $this->signIn($user_1);

        $user_2 = create('App\User', ['name' => 'user_2']);

        $thread = create('App\Thread');

        $reply = raw('App\Reply', ['body' => 'mention user @user_2']);

        $this->postJson($thread->path('/replies'), $reply);
        
        $this->assertCount(1, $user_2->notifications);
    }
    
}
