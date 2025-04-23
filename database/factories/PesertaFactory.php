<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Peserta>
 */
class PesertaFactory extends Factory
{
  public function definition(): array
  {
    return [
      'nik' => fake()->unique()->numerify('##########'),
      'nama' => fake()->name(),
      'occupation' => fake()->company(),
    ];
  }
}
