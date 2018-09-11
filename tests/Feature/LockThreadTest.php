<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LockThreadTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function non_admin_can_not_lock_a_thread()
    {
        $this->signIn()->withExceptionHandling();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->post($thread->path('/lock'), ['lock' => true])->assertStatus(403);

        $this->assertFalse($thread->fresh()->locked);
    }

    /** @test */
    public function admin_can_lock_and_unlock_threads()
    {
        $this->signInAdmin()->withExceptionHandling();

        $thread = create('App\Thread');

        $this->post($thread->path('/lock'), ['lock' => true]);

        $this->assertTrue($thread->fresh()->locked);

        $this->post($thread->path('/lock'), ['lock' => false]);

        $this->assertFalse($thread->fresh()->locked);
    }


    /** @test */
    public function a_locked_thread_can_not_receive_replies()
    {
        $this->signIn();

        $thread = create('App\Thread');

        $thread->lock();

        $this->post($thread->path('/replies'), [
            'body' => 'some body',
            'user_id' => auth()->id()
        ])->assertStatus(423); // http locked
    }

    protected function signInAdmin()
    {
        $user = create('App\User');

        create('App\Role', ['user_id' => $user->id]);

        $this->signIn($user);

        return $this;
    }
}
