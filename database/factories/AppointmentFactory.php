<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $startTime = $this->faker->dateTimeBetween('-1 year', '+1 year');
        
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'start_time' => $startTime,
            'end_time' => Carbon::parse($startTime)->addHours(2), // Correct usage to add hours
            'status' => $this->faker->numberBetween(1, 3)
        ];
    }
}
