<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Shift;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Department>
 */
class DepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'branch_id' => Branch::factory(),
            'shift_id' => Shift::inRandomOrder()->first()->id ?? null,
            'name' => $this->faker->randomElement(['HR', 'Engineering', 'Marketing', 'Finance', 'Sales', 'IT Support']),
            'description' => $this->faker->sentence(),
            'status' => 1,
        ];
    }
}
