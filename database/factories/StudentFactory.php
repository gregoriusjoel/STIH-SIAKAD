<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'npm' => $this->faker->unique()->numerify('2024####'),
            'nama' => $this->faker->name(),
            'prodi' => $this->faker->randomElement(['Ilmu Hukum', 'Hukum Bisnis', 'Hukum Pidana']),
            'angkatan' => $this->faker->randomElement(['2021', '2022', '2023', '2024']),
        ];
    }
}
