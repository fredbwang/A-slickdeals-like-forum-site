<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmYourEmail;
use App\User;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_confirmation_email_is_sent_upon_registration()
    {
        Mail::fake();

        $this->post(route('register'), [
            'name' => 'xx',
            'email' => 'xx@xx.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        Mail::assertQueued(ConfirmYourEmail::class);
    }

    /** @test */
    public function a_confirmation_token_is_set_when_user_resgister()
    {
        $this->post(route('register'), [
            'name' => 'xx',
            'email' => 'xx@xx.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::whereName('xx')->first();

        $this->assertFalse($user->confirmed);

        $this->assertNotNull($user->confirmation_token);
    }


    /** @test */
    public function a_user_can_confirm_its_email_address_through_link()
    {
        $user = factory('App\User')->states('unconfirmed')->create();

        $this->get(route('register.confirm'), ['token' => $user->confirmation_token])
            ->assertRedirect(route('threads'));

        $this->assertTrue($user->fresh()->confirmed);
        $this->assertNull($user->fresh()->confirmation_token);
    }

    /** @test */
    public function it_wont_confirm_invalid_token()
    {
        $this->get(route('register.confirm'), ['token' => 'invalid'])
            ->assertRedirect(route('threads'))
            ->assertSessionHas('flash');
    }

}
