<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $this->get($this->thread->path())
            ->assertSee($reply->body);
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
        
    }
    

}
