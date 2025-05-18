<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        $isRead = $this->faker->boolean(30); // 30% chance of being read
        
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(3),
            'message' => $this->faker->paragraph(),
            'type' => $this->faker->randomElement(['info', 'warning', 'success', 'error']),
            'is_read' => $isRead,
            'read_at' => $isRead ? $this->faker->dateTimeBetween('-1 month', 'now') : null,
        ];
    }
    
    public function unread(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'is_read' => false,
                'read_at' => null,
            ];
        });
    }
}
