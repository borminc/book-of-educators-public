<?php

namespace Database\Factories;

use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $city = City::inRandomOrder()->first();

        return [
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->email(),
            'address_1' => $this->faker->address(),
            'address_2' => null,
            'city_id' => $city->id,
            'country_id' => $city->country_id,
        ];
    }
}
