<?php

namespace Database\Factories;

use App\Models\Approval;
use App\Models\Approver;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ApprovalFactory extends Factory
{
    protected $model = Approval::class;

    public function definition(): array
    {
        $approvers = Approver::pluck('id')->toArray();

        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'level' => $this->faker->randomElement([1, 2]),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'response_date' => Carbon::now(),
            'approver_id' => $this->faker->randomElement($approvers),
        ];
    }
}
