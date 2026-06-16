<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
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
        $faker = \Faker\Factory::create('es_ES');

        $password = Hash::make('12345678');
        $role_id = Role::where('name', 'cliente')->value('id');
        return [
            'name' => $faker->firstName(),
            'lastName' => $faker->lastName(),
            // 'dni' => fake()->unique()->regexify('[0-9]{8}[A-Z]'),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->unique()->numerify("6########"),
            'email_verified_at' => now(),
            'password' => $password,
            'role_id' => $role_id,
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
