<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostLike;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        User::factory()
            ->count(3)
            ->hasPosts(5)
            ->create();

        User::all()->each(function ($user) {
            $random = random_int(5, 10);
            $randomPosts = Post::all()->random($random);
            for($i = 0 ; $i < $random; $i++) {
                PostLike::factory()
                    ->for($randomPosts->all()[$i])
                    ->for($user)
                    ->create();
            }
        });
    }
}
