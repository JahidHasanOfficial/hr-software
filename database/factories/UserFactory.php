<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Designation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $designation = Designation::inRandomOrder()->first() ?? Designation::factory()->create();
        
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'company_id' => $designation->department->branch->company_id,
            'branch_id' => $designation->department->branch_id,
            'department_id' => $designation->department_id,
            'designation_id' => $designation->id,
            'designation' => $designation->name, // Legacy support
            'status' => 'active',
            'joining_date' => fake()->date(),
            'salary' => fake()->randomFloat(2, 3000, 15000),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
