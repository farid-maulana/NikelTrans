<?php

namespace Database\Seeders;

use App\Models\Reservation;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        Reservation::factory()->count(10)->create();
    }
}
