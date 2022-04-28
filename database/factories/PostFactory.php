<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    public function definition()
    {
        return [
            'uuid' => $this->faker->uuid(),
            'description' => $this->faker->sentence(),
            'image' => $this->faker->imageUrl()
        ];
    }
}
