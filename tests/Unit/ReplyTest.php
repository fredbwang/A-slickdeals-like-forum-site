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
    function a_reply_has_an_owner() {
        $reply = factory('App\Reply')->create();
        $this->assertInstanceOf('App\User', $reply->owner);
    }
}
