<?php

namespace Tests\Feature;

use Tests\TestCase;
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

    /** @test */
    public function visitor_can_not_delete_replies()
    {
        $reply = create('App\Reply');

        $this->withExceptionHandling()
            ->delete("replies/{$reply->id}")
            ->assertRedirect('/login');
    }

    /** @test */
    public function a_user_can_only_delete_its_own_replies()
    {
        $this->signIn()->withExceptionHandling();

        $replyOfOtherUser = create('App\Reply');
        $replyOfThisUser = create('App\Reply', ['user_id' => auth()->id()]);

        $this->delete("/replies/{$replyOfOtherUser->id}")
            ->assertStatus(403); //forbidden

        $this->delete("/replies/{$replyOfThisUser->id}")
            ->assertStatus(302);

        // $this->json("delete", "/replies/{$replyOfThisUser->id}")
        //     ->assertStatus(202);

        $this->assertDatabaseMissing('replies', ['id' => $replyOfThisUser->id]);
    }

    /** @test */
    public function a_user_can_delete_replies()
    {
        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $vote = create('App\Vote', ['voted_type' => 'App\Reply', 'voted_id' => $reply->id]);

        $this->delete("replies/{$reply->id}");

        // entities are deleted
        $this->assertDatabaseMissing('replies', ['id' => $reply->id])
            ->assertDatabaseMissing('votes', [
                'subject_id' => $reply->id,
                'subject_type' => get_class($reply)
            ]);

        // activities are deleted
        $this->assertDatabaseMissing('activities', [
            'type' => 'created_reply',
            'subject_id' => $reply->id,
            'subject_type' => 'App\Reply'
        ])
            ->assertDatabaseMissing('activities', [
                'type' => 'created_vote',
                'subject_id' => $vote->id,
                'subject_type' => 'App\Vote'
            ]);
    }

    /** @test */
    public function visitor_can_not_update_replies()
    {
        $reply = create('App\Reply');

        $updatedBody = 'body changed';
        $this->withExceptionHandling()
            ->patch("/replies/{$reply->id}", ['body' => $updatedBody])
            ->assertRedirect('/login');
    }

    /** @test */
    public function user_can_update_its_replies()
    {
        $this->signIn()->withExceptionHandling();

        $replyOfThisUser = create('App\Reply', ['user_id' => auth()->id()]);
        $replyOfOtherUser = create('App\Reply');

        $updatedBody = 'body changed';
        $this->patch("/replies/{$replyOfThisUser->id}", ['body' => $updatedBody]);
        $this->patch("/replies/{$replyOfOtherUser->id}", ['body' => $updatedBody]);

        $this->assertDatabaseHas('replies', ['id' => $replyOfThisUser->id, 'body' => $updatedBody])
            ->assertDatabaseMissing('replies', ['id' => $replyOfOtherUser->id, 'body' => $updatedBody]);
    }

}
