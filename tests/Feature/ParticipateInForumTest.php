<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_visitor_can_not_participate_in_forum_threads()
    {
        $thread = create('App\Thread');

        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->post($thread->path('/replies'), []);
    }

    /** @test */
    function a_user_can_participate_in_forum_threads()
    {
        // $this->be($user = create('App\User'));
        $this->signIn();

        $thread = create('App\Thread');

        $reply = make('App\Reply');

        $this->post($thread->path('/replies'), $reply->toArray());

        $this->get($thread->path())
            ->assertSee($reply->body);
    }

    /** @test */
    public function a_reply_requires_a_body()
    {

        $thread = create('App\Thread');
        $reply = make('App\Reply', ['body' => null]);

        $this->withExceptionHandling()->signIn();

        $this->post($thread->path('/replies'), $reply->toArray())
            ->assertSessionHasErrors('body');

    }

}
