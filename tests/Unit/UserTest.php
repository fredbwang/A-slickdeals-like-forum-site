<?php

namespace Tests\Unit;


use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */
    public function a_user_can_fetch_its_most_recent_reply()
    {
       $user = create('App\User');

       $reply = create('App\Reply', ['user_id' => $user->id]);

       $this->assertTrue($user->lastReply->id == $reply->id);

    }

}
