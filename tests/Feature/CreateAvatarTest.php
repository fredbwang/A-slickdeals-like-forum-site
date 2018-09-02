<?php

namespace Tests\Feature;

use Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;

class CreateAvatarTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_upload_avatar()
    {
        $this->withExceptionHandling();

        $this->postJson('/api/users/1/avatar')
            ->assertStatus(401);
    }

    /** @test */
    public function avatar_must_be_valid()
    {
        $this->withExceptionHandling()->signIn();

        $this->json('post', '/api/users/1/avatar', [
            'avatar' => 'not an image',
        ])->assertStatus(422);
    }

    /** @test */
    public function a_user_can_add_avatar_to_its_profile()
    {
        $this->signIn();

        Storage::fake('public');

        $this->json('post', '/api/users/1/avatar', [
            'avatar' => $file = UploadedFile::fake()->image('avatar.jpg'),
        ]);

        $this->assertEquals('/storage/avatars/' . $file->hashName(), auth()->user()->avatar_path);

        Storage::disk('public')->assertExists('/avatars/' . $file->hashName());
    }

}
