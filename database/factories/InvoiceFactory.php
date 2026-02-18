<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'semester' => $this->faker->numberBetween(1, 8),
            'tahun_ajaran' => $this->faker->randomElement(['2023/2024', '2024/2025']),
            'sks_ambil' => $this->faker->numberBetween(12, 24),
            'paket_sks_bayar' => $this->faker->numberBetween(12, 24),
            'total_tagihan' => $this->faker->randomElement([3000000, 5000000, 7500000, 10000000]),
            'status' => 'DRAFT',
            'allow_partial' => false,
            'notes' => $this->faker->optional()->sentence(),
            'created_by' => User::factory(['role' => 'finance']),
            'published_at' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'PUBLISHED',
            'published_at' => now(),
        ]);
    }

    public function inInstallment(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'IN_INSTALLMENT',
            'published_at' => now(),
        ]);
    }

    public function lunas(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'LUNAS',
            'published_at' => now(),
        ]);
    }
}
