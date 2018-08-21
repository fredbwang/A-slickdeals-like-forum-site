<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    protected $vote_user_pair = [];
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        factory('App\User')->create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => Hash::make('123456'),
        ]);

        $users = factory('App\User', 50)->create();

        $channels = factory('App\Channel', 10)->create();

        $channels->each(function ($channel) {
            $threads = factory('App\Thread', rand(1, 10))->create([
                'channel_id' => $channel->id,
                'user_id' => rand(1, 50)
            ]);

            $threads->each(function ($thread) {
                $this->createActivity($thread, 'created_thread', $thread->user_id);

                $replies = factory('App\Reply', rand(0, 10))->create([
                    'thread_id' => $thread->id,
                    'user_id' => rand(1, 50)
                ]);

                $replies->each(function ($reply) {
                    $this->createActivity($reply, 'created_reply', $reply->user_id);

                    // In order to avoid unique constrain (vote_id, vote_type, user_id) collision,
                    // use hard loop rather than factory multiple whip  
                    for ($i = 0; $i < rand(1, 10); $i++) {
                        $randUserId = $i * 5 + rand(0, 4);

                        $vote = factory('App\Vote', 1)->create([
                            'voted_id' => $reply->id,
                            'voted_type' => 'App\Reply',
                            'user_id' => $randUserId,
                        ]);

                        $this->createActivity($vote->first(), 'created_vote', $randUserId);
                    }
                });
            });
        });
    }

    protected function createActivity($subject, $event, $user_id)
    {
        factory('App\Activity')->create([
            'subject_type' => get_class($subject),
            'subject_id' => $subject->id,
            'user_id' => $user_id,
            'type' => $event,
        ]);
    }
}
