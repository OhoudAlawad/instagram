<?php

use Illuminate\Database\Seeder;

class FollowerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $follow_requests = factory(App\Follower::class, 4)->create();
    }
}