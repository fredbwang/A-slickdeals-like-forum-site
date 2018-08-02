<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    public function setUp()
    {
        parent::setup();
        $this->thread = factory('App\Thread')->create();
    }

    /** @test */
    function a_thread_has_replies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    /** @test */
    function a_thread_has_an_owner()
    {
        $this->assertInstanceOf('App\User', $this->thread->owner);
    }

    /** @test */
    public function can_add_reply_to_thread()
    {
        $user = factory('App\User')->create();
        
        $this->thread->addReply([
            'body' => 'some text',
            'user_id' => $user->id
        ]); 

        $this->assertCount(1, $this->thread->replies);
    }

}
