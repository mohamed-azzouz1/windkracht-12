<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'date_of_birth' => $this->faker->dateTimeBetween('-50 years', '-16 years')->format('Y-m-d'),
            'skill_level' => $this->faker->randomElement(['beginner', 'intermediate', 'advanced']),
            'notes' => $this->faker->optional(0.7)->paragraph(),
        ];
    }
}
