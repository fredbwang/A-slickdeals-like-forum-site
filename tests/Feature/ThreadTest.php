<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $this->thread = factory('App\Thread')->create();
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
        $this->get('/threads/' . $this->thread->id)
            ->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_read_replies_of_a_thread()
    {
        $reply = factory('App\Reply')
            ->create(['thread_id' => $this->thread->id]);
        $this->get('/threads/' . $this->thread->id)
            ->assertSee($reply->body);
    }
}
