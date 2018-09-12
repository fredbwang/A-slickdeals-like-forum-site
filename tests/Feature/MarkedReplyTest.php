<?php

namespace Tests\Feature;

use Tests\TestCase;

class MarkedReplyTest extends TestCase
{
    /** @test */
    public function user_can_mark_repl_with_a_tag()
    {
        $this->signIn();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $replies = create('App\Reply', ['thread_id' => $thread->id], 3);

        $this->assertFalse($replies[1]->fresh()->isMarked('best'));

        $this->postJson(route('reply.mark', [$replies[1]->id, 'best']));

        $this->assertTrue($replies[1]->fresh()->isMarked('best'));
    }

    /** @test */
    public function only_creator_can_mark_reply_as_best()
    {
        $this->signIn()->withExceptionHandling();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $replies = create('App\Reply', ['thread_id' => $thread->id], 3);

        $this->signIn(create('App\User'));

        $this->postJson(route('reply.mark', [$replies[1]->id, 'best']))->assertStatus(403);

        $this->assertFalse($replies[1]->fresh()->isMarked('best'));
    }

    /** @test */
    public function if_a_best_reply_is_deleted_then_the_thread_will_set_best_reply_id_to_null()
    {
        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $reply->thread->markBestReply($reply);

        $this->deleteJson(route('reply.destroy', $reply));

        $this->assertNull($reply->thread->fresh()->best_reply_id);
    }

}
