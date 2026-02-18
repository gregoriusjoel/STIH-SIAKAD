<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstallmentRequestFactory extends Factory
{
    public function definition(): array
    {
        return [
            'invoice_id' => Invoice::factory(),
            'student_id' => Student::factory(),
            'requested_terms' => $this->faker->numberBetween(2, 12),
            'approved_terms' => null,
            'alasan' => $this->faker->paragraph(),
            'status' => 'SUBMITTED',
            'reviewed_by' => null,
            'reviewed_at' => null,
            'rejection_reason' => null,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'APPROVED',
            'approved_terms' => $attributes['requested_terms'] ?? 3,
            'reviewed_by' => \App\Models\User::factory(['role' => 'finance']),
            'reviewed_at' => now(),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'REJECTED',
            'reviewed_by' => \App\Models\User::factory(['role' => 'finance']),
            'reviewed_at' => now(),
            'rejection_reason' => $this->faker->sentence(),
        ]);
    }
}
