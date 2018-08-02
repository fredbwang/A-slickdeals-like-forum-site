<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_visitor_can_not_create_a_thread()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $thread = factory('App\Thread')->make(); // raw create an array and make create model

        $this->post('/threads', $thread->toArray());

    }

    /** @test */
    public function a_user_can_create_a_thread()
    {
        $this->actingAs(factory('App\User')->create());

        $thread = factory('App\Thread')->make(); // raw create an array and make create model

        $this->post('/threads', $thread->toArray());

        $this->get($thread->path())
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
