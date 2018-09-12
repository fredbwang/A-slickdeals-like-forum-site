<?php

namespace Tests\Unit;

use App\Activity;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    /** @test */
    public function it_record_activity_when_thread_is_created()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->assertDatabaseHas('activities', [
            'user_id' => $thread->user_id,
            'type' => 'created_thread',
            'subject_id' => $thread->id,
            'subject_type' => 'App\Thread'
        ]);

        // test the relationship between activity and subject
        $activity = Activity::first();

        $this->assertEquals($activity->subject->id, $thread->id);
    }

    /** @test */
    public function it_record_activity_when_reply_is_created()
    {
        $this->signIn();

        create('App\Reply');

        $this->assertEquals(Activity::count(), 2);
    }


    /** @test */
    public function it_fecthes_feeds_for_a_user()
    {
        $this->signIn();

        create('App\Thread', ['user_id' => auth()->id()], 2);

        // simulate an creation a week before;
        auth()->user()->activities()->first()->update(['created_at' => now()->subWeek()]);

        $feed = Activity::feed(auth()->user());

        $this->assertTrue($feed->keys()->contains(
            now()->format('Y-m-d')
        ));

        $this->assertTrue($feed->keys()->contains(
            now()->subWeek()->format('Y-m-d')
        ));
    }

}
