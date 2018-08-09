<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProfileTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_has_a_profile()
    {
        $this->assertTrue(true);
    }
}
