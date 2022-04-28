<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PostLikeFactory extends Factory
{
    public function definition()
    {
        return [
            'uuid' => $this->faker->uuid()
        ];
    }
}
