<?php

namespace Database\Factories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstallmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'invoice_id' => Invoice::factory(),
            'installment_no' => 1,
            'amount' => $this->faker->randomElement([1500000, 1666000, 2000000]),
            'due_date' => $this->faker->dateTimeBetween('now', '+3 months'),
            'status' => 'UNPAID',
            'paid_at' => null,
        ];
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'PAID',
            'paid_at' => now(),
        ]);
    }

    public function waitingVerification(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'WAITING_VERIFICATION',
        ]);
    }
}
