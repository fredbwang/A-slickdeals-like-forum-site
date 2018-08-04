<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Faker\Factory as Faker;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    public function setUp()
    {
        parent::setup();
        $this->thread = create('App\Thread');
    }

    /** @test */
    public function path_method_works_as_expected()
    {
        $thread = create('App\Thread');

        $this->assertEquals("/threads/{$thread->channel->slug}/{$thread->id}", $thread->path());

        $suffix = Faker::create()->word;
        $this->assertEquals("/threads/{$thread->channel->slug}/{$thread->id}{$suffix}", $thread->path($suffix));
    }


    /** @test */
    function a_thread_has_an_owner()
    {
        $this->assertInstanceOf('App\User', $this->thread->owner);
    }

    /** @test */
    function a_thread_has_replies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    /** @test */
    public function can_add_reply_to_thread()
    {
        $user = create('App\User');

        $this->thread->addReply([
            'body' => 'some text',
            'user_id' => $user->id
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    /** @test*/
    public function a_thread_belongs_to_a_channel()
    {
        $thread = create('App\Thread');
        $this->assertInstanceOf('App\Channel', $thread->channel);
    }
}
