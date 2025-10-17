<?php

// database/factories/UserFactory.php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        // Génération matricule unique : UIS + 5 chiffres
        do {
            $digits = random_int(0, 99999);
            $matricule = sprintf('UIS%05d', $digits);
        } while (User::where('matricule', $matricule)->exists());

        return [
            'matricule'  => $matricule,
            'first_name' => $this->faker->firstName(),
            'last_name'  => $this->faker->lastName(),
            'email'      => $this->faker->unique()->safeEmail(),
            'password'   => static::$password ??= Hash::make('password'),
            'phone'      => $this->faker->phoneNumber(),
            'location'   => $this->faker->city(),
            'is_locked'  => false,
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
