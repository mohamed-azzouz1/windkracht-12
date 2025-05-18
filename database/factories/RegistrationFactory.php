<?php

namespace Database\Factories;

use App\Models\Instructor;
use App\Models\Package;
use App\Models\Registration;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegistrationFactory extends Factory
{
    protected $model = Registration::class;

    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-1 month', '+2 months');
        $endDate = $this->faker->optional(0.8)->dateTimeBetween($startDate, '+3 months');
        
        return [
            'student_id' => Student::factory(),
            'package_id' => Package::factory(),
            'instructor_id' => $this->faker->optional(0.8)->randomElement([null, Instructor::factory()]),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'cancelled', 'completed']),
            'notes' => $this->faker->optional(0.5)->paragraph(),
        ];
    }
    
    public function confirmed(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'confirmed',
            ];
        });
    }
    
    public function completed(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'completed',
                'end_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            ];
        });
    }
}
