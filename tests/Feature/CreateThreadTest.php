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
    public function a_visitor_can_not_see_create_thread_page()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->get('/threads/create');
    }

    /** @test */
    public function a_visitor_can_not_create_a_thread()
    {
        $thread = make('App\Thread'); // raw create an array and make create model

        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->post('/threads', $thread->toArray());
    }

    /** @test */
    public function a_user_can_create_a_thread()
    {
        $this->signIn();

        $thread = raw('App\Thread');

        $response = $this->post('/threads', $thread);

        $this->get($response->headers->get('Location'))
            ->assertSee($thread['title'])
            ->assertSee($thread['body']);
    }

    /** @test */
    public function a_thread_requires_a_title()
    {
        $this->postThread(['title' => null])
            ->assertSessionHasErrors('title');

    }

    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->postThread(['body' => null])
            ->assertSessionHasErrors('body');

    }

    /** @test */
    public function a_thread_requires_a_valid_channel()
    {
        factory('App\Channel', 3)->create();
        $this->postThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

            $this->postThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');

    }

    public function postThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();

        $thread = raw('App\Thread', $overrides);

        return $this->post('/threads', $thread);
    }

}
