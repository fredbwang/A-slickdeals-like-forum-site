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

        $this->withExceptionHandling()->post('replies/' . $reply->id . '/vote-up')
            ->assertRedirect('/login');
    }

    /** @test */
    public function a_user_can_vote_a_reply()
    {
        $this->signIn();

        $reply = create('App\Reply');
        $this->post('/replies/' . $reply->id . '/vote-up');

        $this->assertCount(1, $reply->votes);
    }

    /** @test */
    public function a_user_can_only_vote_a_reply_once()
    {
        $this->signIn();

        $reply = create('App\Reply');
        try {
            $this->post('/replies/' . $reply->id . '/vote-up');
            $this->post('/replies/' . $reply->id . '/vote-up');
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

        $this->post('/replies/' . $reply->id . '/vote-up');
        $reply->load('votes');
        $this->assertTrue($reply->isUpVote());

        $this->post('/replies/' . $reply->id . '/vote-down');
        $reply->load('votes');
        $this->assertTrue($reply->isDownVote());
    }
}
