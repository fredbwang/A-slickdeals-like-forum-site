<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class VoteTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_visitor_can_not_vote()
    {
        $reply = create('App\Reply');

        $this->withExceptionHandling()->post($reply->votePath())
            ->assertRedirect('/login');
    }

    /** @test */
    public function a_user_can_vote_a_reply()
    {
        $this->signIn();

        $reply = create('App\Reply');
        $this->post($reply->votePath());

        $this->assertCount(1, $reply->votes);
    }

    /** @test */
    public function a_user_can_only_vote_a_reply_once()
    {
        $this->signIn();

        $reply = create('App\Reply');
        try {
            $this->post($reply->votePath());
            $this->post($reply->votePath());
        } catch (\Exception $e) {
            $this->fail('A user can not vote a reply more than once.');
        }

        $this->assertCount(1, $reply->votes);
    }

    /** @test */
    public function a_user_can_vote_up_or_down_a_reply()
    {
        $this->signIn();

        $reply = create('App\Reply');

        $this->post($reply->votePath('vote-up'));
        $reply->load('votes');
        $this->assertTrue($reply->isUpVote());

        $this->post($reply->votePath('vote-down'));
        $reply->load('votes');
        $this->assertTrue($reply->isDownVote());
    }

    /** @test */
    public function a_user_can_cancel_a_vote()
    {
        $this->signIn();

        $reply = create('App\Reply');

        $this->post($reply->votePath('vote-up'));
        $reply->load('votes');
        $this->assertEquals(1, $reply->getCurrentVoteAttribute());

        $this->post($reply->votePath('cancel-vote'));
        $reply->load('votes');
        $this->assertEquals(0, $reply->getCurrentVoteAttribute());
    }
}
