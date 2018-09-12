<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class SubscribeTest extends TestCase
{
    /** @test */
    public function a_user_can_subscribe_to_a_thread()
    {
        $this->signIn();

        $thread = create('App\Thread');

        $this->post($thread->path('/subscribe'));

        $this->assertCount(1, $thread->fresh()->subscriptions);
    }

     /** @test */
     public function a_user_can_unsubscribe_from_a_thread()
     {
         $this->signIn();
 
         $thread = create('App\Thread');
 
         $thread->subscribedBy();

         $this->delete($thread->path('/subscribe'));
 
         $this->assertCount(0, $thread->subscriptions);
     }
}
