<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Redis;
use App\Trending;

class TrendingThreadTest extends TestCase
{

    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        $this->trending = new Trending();

        $this->trending->reset();
    }


    /** @test */
    public function it_increments_redis_records_when_thread_page_is_visited()
    {
        $this->assertCount(0, $this->trending->get());

        $thread = create('App\Thread');

        $this->get($thread->path());

        $this->assertCount(1, $trendingThreads = $this->trending->get());

        $this->assertEquals($thread->title, $trendingThreads[0]->title);
    }

}
