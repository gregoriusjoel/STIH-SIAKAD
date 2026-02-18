<?php

namespace Database\Factories;

use App\Models\Installment;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentProofFactory extends Factory
{
    public function definition(): array
    {
        return [
            'invoice_id' => Invoice::factory(),
            'installment_id' => Installment::factory(),
            'uploaded_by' => User::factory(['role' => 'student']),
            'transfer_date' => $this->faker->dateTimeBetween('-7 days', 'now'),
            'amount_submitted' => 1666000,
            'method' => $this->faker->randomElement(['Transfer Bank', 'VA', 'E-Wallet']),
            'file_path' => 'payment-proofs/test_proof.jpg',
            'status' => 'UPLOADED',
            'finance_notes' => null,
            'approved_by' => null,
            'approved_at' => null,
            'rejected_at' => null,
            'student_notes' => $this->faker->optional()->sentence(),
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'APPROVED',
            'approved_by' => User::factory(['role' => 'finance']),
            'approved_at' => now(),
            'finance_notes' => $this->faker->optional()->sentence(),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'REJECTED',
            'approved_by' => User::factory(['role' => 'finance']),
            'rejected_at' => now(),
            'finance_notes' => $this->faker->sentence(),
        ]);
    }
}
