<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReadThreadTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $this->thread = create('App\Thread');
    }

    /** @test */
    public function a_user_can_route_to_threads()
    {
        $this->get('/threads')
            ->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_route_to_a_single_thread()
    {
        $this->get($this->thread->path())
            ->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_read_replies_of_a_thread()
    {
        $reply = create('App\Reply', ['thread_id' => $this->thread->id]);

        $response = $this->getJson($this->thread->path('/replies'))->json();
        $this->assertEquals($response['data'][0]['body'], $reply->body);
    }

    /** @test */
    public function a_user_can_filter_threads_by_channel()
    {
        $channel = create('App\Channel');
        $threadWithInChannel = create('App\Thread', ['channel_id' => $channel->id]);
        $threadOutOfChannel = create('App\Thread');

        $this->get('threads/' . $channel->slug)
            ->assertSee($threadWithInChannel->title)
            ->assertDontSee($threadOutOfChannel->title);
    }

    /** @test */
    public function a_user_can_filter_by_channel_and_see_channel_name()
    {
        $channel = create('App\Channel');

        $this->get('threads/' . $channel->slug)
            ->assertSee($channel->name);
    }

    /** @test */
    public function a_user_can_filter_threads_by_username()
    {
        $this->signIn();

        $myThread = create('App\Thread', ['user_id' => auth()->id()]);
        $otherThread = create('App\Thread');

        $this->get('/threads?createBy=' . auth()->user()->name)
            ->assertSee($myThread->title)
            ->assertDontSee($otherThread->title);
    }

    /** @test */
    public function a_user_can_filter_threads_by_username_and_channel()
    {
        $this->signIn();

        $user = create('App\User');
        $channel = create('App\Channel');

        $myThreadWithInChannel = create('App\Thread', ['user_id' => auth()->id(), 'channel_id' => $channel->id]);
        $myThreadOutOfChannel = create('App\Thread', ['user_id' => auth()->id()]);
        $otherThreadwithInChannel = create('App\Thread', ['channel_id' => $channel->id]);
        $otherThreadOutOfChannel = create('App\Thread');

        $this->get('threads/' . $channel->slug . '?createBy=' . auth()->user()->name)
            ->assertSee($myThreadWithInChannel->title)
            ->assertDontSee($myThreadOutOfChannel->title)
            ->assertDontSee($otherThreadwithInChannel->title)
            ->assertDontSee($otherThreadOutOfChannel->title);
    }

    /** @test */
    public function a_user_can_filter_threads_by_popularity()
    {
        $threadWithTwoReplies = create('App\Thread', ['created_at' => now()->addMinutes(2)]);
        create('App\Reply', ['thread_id' => $threadWithTwoReplies], 2);

        $threadWithThreeReplies = create('App\Thread', ['created_at' => now()->addMinutes(1)]);
        create('App\Reply', ['thread_id' => $threadWithThreeReplies], 10);

        $threadWithNoReplies = $this->thread;

        $response = $this->get('/threads?popular=1');

        $threadsFromResponse = $response->baseResponse->original->getData()['threads'];

        $this->assertEquals([10, 2, 0], $threadsFromResponse->pluck('replies_count')->toArray());
    }

    /** @test */
    public function a_user_can_filter_threads_by_popularity_and_channel()
    {
        $channel = create('App\Channel');
        $threadWithTwoReplies = create('App\Thread', ['created_at' => now()->addMinutes(2), 'channel_id' => $channel->id]);
        create('App\Reply', ['thread_id' => $threadWithTwoReplies->id], 2);

        $threadWithThreeReplies = create('App\Thread', ['created_at' => now()->addMinutes(1), 'channel_id' => $channel->id]);
        create('App\Reply', ['thread_id' => $threadWithThreeReplies->id], 10);

        $threadWithNoReplies = create('App\Thread', ['channel_id' => $channel->id]);

        $threadOutOfChannel = $this->thread;
        create('App\Reply', ['thread_id' => $threadOutOfChannel->id], 3);

        $response = $this->get('/threads/' . $channel->slug . '?popular=1');

        $threadsFromResponse = $response->baseResponse->original->getData()['threads'];

        $this->assertEquals([10, 2, 0], $threadsFromResponse->pluck('replies_count')->toArray());
    }

    /** @test */
    public function a_user_can_filter_threads_by_commented_or_not()
    {
        $thread = create('App\Thread');
        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $response = $this->getJson('/threads?uncommented=1')->Json();

        $this->assertCount(1, $response);
    }

    /** @test */
    public function a_user_can_get_all_replies_of_a_thread()
    {
        $thread = create('App\Thread');

        $reply = create('App\Reply', ['thread_id' => $thread->id], 2);

        $response = $this->getJson($thread->path('/replies'))->json();

        $this->assertEquals(2, $response['total']);
    }
}
