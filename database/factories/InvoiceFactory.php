<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Registration;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        $amount = $this->faker->numberBetween(100, 1000);
        $taxRate = 0.21; // 21% VAT
        $taxAmount = $amount * $taxRate;
        $totalAmount = $amount + $taxAmount;
        
        $invoiceDate = $this->faker->dateTimeBetween('-6 months', 'now');
        $dueDate = (clone $invoiceDate)->modify('+30 days');
        
        return [
            'registration_id' => Registration::factory(),
            'invoice_number' => 'INV-' . $this->faker->unique()->numberBetween(1000, 9999),
            'invoice_date' => $invoiceDate,
            'due_date' => $dueDate,
            'amount' => $amount,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'status' => $this->faker->randomElement(['draft', 'sent', 'paid', 'overdue', 'cancelled']),
        ];
    }
    
    public function paid(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'paid',
            ];
        });
    }
    
    public function overdue(): self
    {
        return $this->state(function (array $attributes) {
            $invoiceDate = $this->faker->dateTimeBetween('-6 months', '-2 months');
            $dueDate = (clone $invoiceDate)->modify('+30 days');
            
            return [
                'status' => 'overdue',
                'invoice_date' => $invoiceDate,
                'due_date' => $dueDate,
            ];
        });
    }
}
