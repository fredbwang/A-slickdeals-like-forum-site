<?php

namespace Tests\Feature;

use Tests\TestCase;
use \Illuminate\Notifications\DatabaseNotification;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserNotificationTest extends TestCase
{

    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $this->signIn();
    }

    /** @test */
    public function a_notification_is_prepared_when_a_subscribed_thread_is_replied_not_by_current_user()
    {
        $thread = create('App\Thread')->subscribedBy();

        $this->assertCount(0, auth()->user()->notifications);

        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'A reply',
        ]);

        $this->assertCount(0, auth()->user()->fresh()->notifications);

        $thread->addReply([
            'user_id' => $user = create('App\User'),
            'body' => 'A reply',
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    /** @test */
    public function a_user_can_fetch_all_unread_notifications()
    {
        create(DatabaseNotification::class);

        $response = $this->getJson("/profiles/" . auth()->user()->name . "/notifications")->json();

        $this->assertCount(1, $response);
    }


    /** @test */
    public function a_user_can_mark_one_of_its_notifications_as_read()
    {
        $notification = create(DatabaseNotification::class);

        $user = auth()->user();

        $this->assertCount(1, $user->fresh()->unReadNotifications);

        $this->delete("/profiles/{$user->name}/notifications/{$notification->id}");

        $this->assertCount(0, $user->fresh()->unReadNotifications);
    }

}
