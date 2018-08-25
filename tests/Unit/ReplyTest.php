<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReplyTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */
    function a_reply_has_an_owner()
    {
        $reply = create('App\Reply');
        $this->assertInstanceOf('App\User', $reply->owner);
    }

    /** @test */
    public function path_method_works_as_expected()
    {
        $thread = create('App\Thread');
        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $replyPath = "/threads/{$thread->channel->slug}/{$thread->id}#reply-{$reply->id}";
        $this->assertEquals($replyPath, $reply->path());
    }

    /** @test */
    public function it_can_tell_if_it_was_just_posted()
    {
        $reply = create('App\Reply');

        $this->assertTrue($reply->wasJustCreated());
        
        $oldReply = create('App\Reply', ['created_at' => now()->subMinutes(30)]);

        $this->assertFalse($oldReply->wasJustCreated());
    }
    

}
