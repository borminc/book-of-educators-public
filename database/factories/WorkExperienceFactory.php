<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkExperience>
 */
class WorkExperienceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'company' => $this->faker->company(),
            'title' => $this->faker->jobTitle(),
            'location' => $this->faker->city(),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->randomElement([$this->faker->date(), null]),
            'description' => $this->faker->paragraph(),
        ];
    }
}
