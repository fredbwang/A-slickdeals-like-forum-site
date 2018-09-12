<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class ChannelTest extends TestCase
{
    /** @test */
    public function a_channel_has_threads()
    {
        $channel = create('App\Channel');

        $thread = create('App\Thread', ['channel_id' => $channel->id]);

        $this->assertTrue($channel->threads->contains($thread));
    }
    
}
