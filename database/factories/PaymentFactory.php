<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'invoice_id' => Invoice::factory(),
            'payment_method' => $this->faker->randomElement(['credit_card', 'bank_transfer', 'paypal', 'ideal', 'cash']),
            'transaction_id' => $this->faker->optional(0.8)->uuid(),
            'amount' => $this->faker->numberBetween(100, 1000),
            'payment_date' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'status' => $this->faker->randomElement(['pending', 'completed', 'failed', 'refunded']),
            'notes' => $this->faker->optional(0.3)->sentence(),
        ];
    }
    
    public function completed(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'completed',
            ];
        });
    }
}
