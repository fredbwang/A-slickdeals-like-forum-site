<?php

namespace Tests\Feature;

use Tests\TestCase;
use Exception;

class ParticipateInForumTest extends TestCase
{
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

        $response = $this->getJson($thread->path('/replies'))->json();
        $this->assertEquals($response['data'][0]['body'], $reply->body);
    }

    /** @test */
    public function a_reply_requires_a_body()
    {

        $thread = create('App\Thread');
        $reply = raw('App\Reply', ['body' => null]);

        $this->withExceptionHandling()->signIn();

        $response = $this->postJson($thread->path('/replies'), $reply)
            ->assertStatus(422);
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

    /** @test */
    public function replies_containing_spam_is_not_allowed_to_be_created()
    {
        $this->withExceptionHandling()->signIn();

        $thread = create('App\Thread');

        $reply = raw('App\Reply', ['body' => 'spam']);
        
        $this->postJson($thread->path('/replies'), $reply)->assertStatus(422);
    }
    
    /** @test */
    public function a_user_can_only_reply_once_per_minute()
    {
        $this->signIn();
    
        $thread = create('App\Thread');
    
        $reply = raw('App\Reply', ['body' => 'some reply']);
        
        $this->postJson($thread->path('/replies'), $reply)->assertStatus(201); // 201 created
        $this->postJson($thread->path('/replies'), $reply)->assertStatus(429); // too many requests
    }
    

}
