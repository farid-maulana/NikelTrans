<?php

namespace Database\Factories;

use App\Models\Approver;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ApproverFactory extends Factory
{
    protected $model = Approver::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'title' => $this->faker->jobTitle(),
            'phone_number' => $this->faker->regexify('08\d{10}'),
        ];
    }
}
