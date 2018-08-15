<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Activity;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ManageThreadTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setup();
        // $this->thread = create('App\Thread');
    }

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
    public function a_visitor_can_not_delete_threads()
    {
        $this->withExceptionHandling();

        $thread = create('App\Thread');

        $this->delete($thread->path())
            ->assertRedirect('/login');
    }

    /** @test */
    public function a_user_can_delete_threads()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $this->delete($thread->path())->assertStatus(302); // assert redirect

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['thread_id' => $thread->id]);

        $this->assertEquals(0, Activity::count());
    }

    /** @test */
    public function a_user_can_only_delete_its_own_thread()
    {
        $this->withExceptionHandling()->signIn();

        $threadOfThisUser = create('App\Thread', ['user_id' => auth()->id()]);

        $this->delete($threadOfThisUser->path())->assertStatus(302);
        $this->assertDatabaseMissing('threads', ['id' => $threadOfThisUser->id]);

        $threadOfOtherUser = create('App\Thread');

        $this->delete($threadOfOtherUser->path())->assertStatus(403);
        $this->assertDatabaseHas('threads', ['id' => $threadOfOtherUser->id]);
    }

    /** @test */
    public function a_user_can_only_see_delete_btn_when_authorized()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->get($thread->path())->assertSee('id="delete-btn"');

        $thread = create('App\Thread');

        $this->get($thread->path())->assertDontSee('id="delete-btn"');
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
