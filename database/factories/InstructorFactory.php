<?php

namespace Database\Factories;

use App\Models\Instructor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstructorFactory extends Factory
{
    protected $model = Instructor::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'certification' => $this->faker->randomElement(['IKO Level 1', 'IKO Level 2', 'IKO Level 3', 'Advanced Certification']),
            'years_of_experience' => $this->faker->numberBetween(1, 15),
            'is_active' => $this->faker->boolean(75), // 75% chance of being active
        ];
    }
}
