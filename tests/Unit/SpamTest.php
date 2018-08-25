<?php

namespace Tests\Unit;

use App\Inspections\Spam;

use Tests\TestCase;

class SpamTest extends TestCase
{
    /** @test */
    public function it_validates_against_invalid_keywords()
    {
        $spam = new Spam();

        $this->assertFalse($spam->detect('an ok reply'));

        $this->expectException(\Exception::class);
        $spam->detect('an reply with spam');
    }

    /** @test */
    public function it_validate_against_key_held_down()
    {
        $spam = new Spam();

        $this->assertFalse($spam->detect('an ok reply'));

        $this->expectException(\Exception::class);

        $spam->detect('aaaaaaaaaaaaa');
    }
    
    
}
