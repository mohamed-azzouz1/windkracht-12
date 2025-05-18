<?php

namespace Database\Factories;

use App\Models\Instructor;
use App\Models\Kitesurfer;
use App\Models\Registration;
use Illuminate\Database\Eloquent\Factories\Factory;

class KitesurferFactory extends Factory
{
    protected $model = Kitesurfer::class;

    public function definition(): array
    {
        $hasOwnEquipment = $this->faker->boolean(30); // 30% chance of having own equipment
        
        return [
            'registration_id' => Registration::factory(),
            'instructor_id' => Instructor::factory(),
            'skill_level' => $this->faker->randomElement(['beginner', 'intermediate', 'advanced']),
            'has_own_equipment' => $hasOwnEquipment,
            'equipment_needs' => $hasOwnEquipment ? null : $this->faker->sentence(),
        ];
    }
    
    public function beginner(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'skill_level' => 'beginner',
                'has_own_equipment' => false,
                'equipment_needs' => 'Needs full equipment set for beginners',
            ];
        });
    }
    
    public function advanced(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'skill_level' => 'advanced',
                'has_own_equipment' => true,
            ];
        });
    }
}
