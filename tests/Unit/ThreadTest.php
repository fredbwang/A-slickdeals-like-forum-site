<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Notification;
use Faker\Factory as Faker;
use App\Notifications\ThreadUpdated;
use Illuminate\Support\Facades\Redis;

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

    /** @test */
    public function can_notify_subscribers_when_reply_added()
    {
        Notification::fake();

        $this->signIn();

        $this->thread
            ->subscribedBy()
            ->addReply([
                'body' => 'some text',
                'user_id' => $userId = create('App\User')->id,
            ]);

        Notification::assertSentTo(auth()->user(), ThreadUpdated::class);
    }


    /** @test*/
    public function a_thread_belongs_to_a_channel()
    {
        $thread = create('App\Thread');
        $this->assertInstanceOf('App\Channel', $thread->channel);
    }

    /** @test */
    public function a_thread_can_be_subscribed_to()
    {
        $thread = create('App\Thread');

        $thread->subscribedBy($userId = 1);

        $this->assertEquals(1, $thread->subscriptions()->where('user_id', $userId)->count());
    }

    /** @test */
    public function a_thread_can_be_unsubscribed_from()
    {
        $thread = create('App\Thread');

        $thread->subscribedBy($userId = 1);
        $thread->unsubscribedFrom($userId = 1);

        $this->assertEquals(0, $thread->subscriptions()->where('user_id', $userId)->count());
    }

    /** @test */
    public function it_knows_whether_it_is_subscribed_by_a_user()
    {
        $this->signIn();

        $this->assertFalse($this->thread->isSubscribedBy);

        $this->thread->subscribedBy(auth()->id());

        $this->assertTrue($this->thread->isSubscribedBy);
    }

    /** @test */
    public function it_records_each_visit()
    {
        $thread = make('App\Thread', ['id' => 1]);

        $thread->visits()->reset();

        $thread->visits()->record();

        $this->assertEquals(1, $thread->visits()->count());

        $thread->visits()->record();

        $this->assertEquals(2, $thread->visits()->count());
    }

}
