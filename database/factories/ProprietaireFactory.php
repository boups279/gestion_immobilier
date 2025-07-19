<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProprietaireFactory extends Factory
{
    public function definition()
    {
        return [
            'nom' => $this->faker->lastName,
            'prenom' => $this->faker->firstName,
            'email' => $this->faker->unique()->safeEmail,
            'telephone' => $this->faker->phoneNumber,
            'adresse' => $this->faker->address,
            'date_naissance' => $this->faker->date('Y-m-d', '2000-01-01')
        ];
    }
}