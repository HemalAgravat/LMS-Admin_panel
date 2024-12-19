<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3, true),
            'author' => $this->faker->name(),
            'isbn' => $this->faker->unique()->isbn13(),
            'publication_date' => $this->faker->dateTimeBetween('-10 years', 'now')->format('Y-m-d'),
            'availability_status' => true,
        ];
    }
}
