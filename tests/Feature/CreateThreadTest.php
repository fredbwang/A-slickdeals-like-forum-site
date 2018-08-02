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
        $this->withExceptionHandling()
            ->get('/threads/create')
            ->assertRedirect('login');
    }

    /** @test */
    public function a_visitor_can_not_create_a_thread()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $thread = make('App\Thread'); // raw create an array and make create model

        $this->post('/threads', $thread->toArray());

    }

    /** @test */
    public function a_user_can_create_a_thread()
    {
        $this->signIn();

        $thread = make('App\Thread'); // raw create an array and make create model

        $this->post('/threads', $thread->toArray());

        $this->get($thread->path())
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
