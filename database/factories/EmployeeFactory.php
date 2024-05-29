<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'name' => $this->faker->name(),
            'license_number' => $this->faker->regexify('\d{4}-\d{4}-\d{6}'),
            'phone_number' => $this->faker->regexify('08\d{10}'),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'photo' => $this->faker->imageUrl(640, 480, 'people'),
        ];
    }
}
