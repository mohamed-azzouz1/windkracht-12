<?php

namespace Database\Factories;

use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;

class PackageFactory extends Factory
{
    protected $model = Package::class;

    public function definition(): array
    {
        $price = $this->faker->numberBetween(100, 800);
        $originalPrice = $this->faker->optional(0.7)->numberBetween($price, $price + 200);
        
        return [
            'name' => 'Kitesurf ' . $this->faker->randomElement(['Beginner', 'Intermediate', 'Advanced', 'Professional']) . ' Package',
            'description' => $this->faker->paragraph(),
            'price' => $price,
            'original_price' => $originalPrice,
            'duration_hours' => $this->faker->randomElement([2, 3, 4, 8, 12]),
            'number_of_sessions' => $this->faker->randomElement([1, 3, 5, 10]),
            'max_participants' => $this->faker->randomElement([1, 2, 4, 6]),
            'is_active' => $this->faker->boolean(90), // 90% chance of being active
        ];
    }
}
