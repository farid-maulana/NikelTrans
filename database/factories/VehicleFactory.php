<?php

namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

    public function definition(): array
    {
        $vehicleBrands = [
            'Toyota Land Cruiser', 'Ford Ranger', 'Nissan Navara', 'Mitsubishi Triton', 'Caterpillar', 'Komatsu', 'Volvo', 'Hitachi'
        ];

        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'license_plate' => strtoupper($this->faker->regexify('[A-Z] \d{4} [A-Z]{3}')),
            'brand' => $this->faker->randomElement($vehicleBrands),
            'type' => $this->faker->randomElement(['personnel', 'cargo']),
            'ownership' => $this->faker->randomElement(['owned', 'rented']),
            'status' => $this->faker->randomElement(['available', 'unavailable', 'on maintenance']),
        ];
    }
}
