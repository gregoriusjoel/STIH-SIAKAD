<?php

namespace Database\Factories;

use App\Models\Installment;
use App\Models\Invoice;
use App\Models\PaymentProof;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'invoice_id' => Invoice::factory(),
            'installment_id' => Installment::factory(),
            'proof_id' => PaymentProof::factory(),
            'amount_approved' => 1666000,
            'paid_date' => now()->toDateString(),
            'transfer_date' => $this->faker->dateTimeBetween('-7 days', 'now')->format('Y-m-d'),
            'approved_by' => User::factory(['role' => 'finance']),
        ];
    }
}
