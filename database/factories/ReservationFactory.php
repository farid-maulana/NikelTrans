<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition(): array
    {
        $employees = Employee::pluck('id')->toArray();
        $vehicles = Vehicle::pluck('id')->toArray();
        $users = User::where('role', 'admin')->pluck('id')->toArray();
        $start_date = $this->faker->dateTimeBetween('-1 month', 'now');
        $end_date = $this->faker->dateTimeBetween($start_date, 'now + 1 month');

        return [
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
          'start_date' => Carbon::instance($start_date),
          'end_date' => Carbon::instance($end_date),
          'status' => $this->faker->randomElement(['pending', 'on process', 'approved', 'rejected']),
          'employee_id' => $this->faker->randomElement($employees),
          'vehicle_id' => $this->faker->randomElement($vehicles),
          'user_id' => $this->faker->randomElement($users),
        ];
    }
}
