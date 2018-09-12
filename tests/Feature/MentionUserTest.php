<?php

namespace Tests\Feature;

use Tests\TestCase;

class MentionUserTest extends TestCase
{
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

    /** @test */
    public function it_can_fetch_mentioned_users_by_prefix()
    {
        create('App\User', ['name' => 'abc']);
        create('App\User', ['name' => 'abd']);
        create('App\User', ['name' => 'acd']);

        $response = $this->getJson('/api/users?name=ab')->json();
        $this->assertEquals(['abc', 'abd'], $response);
    }


}
