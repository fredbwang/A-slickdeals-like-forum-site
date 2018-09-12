<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Thread;

class SearchTest extends TestCase
{
    /** @test */
    public function a_user_can_search_threads()
    {
        config(['scout.driver' => 'algolia']);

        $keyword = "123";
        $num = 5;

        $threads = create('App\Thread', [], 2);
        $threadsWithKeyWords = create('App\Thread', ['body' => "body with keyword {$keyword}"], $num);

        do {
            sleep(.1);
            $results = $this->getJson("/threads/search?keyword={$keyword}")->json()['data'];
        } while (empty($results));

        $this->assertCount($num, $results);

        Thread::latest()->take($num + 2)->unsearchable();
    }

}
