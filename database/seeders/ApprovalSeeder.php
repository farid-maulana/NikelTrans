<?php

namespace Database\Seeders;

use App\Models\Approval;
use App\Models\Approver;
use App\Models\Reservation;
use Illuminate\Database\Seeder;

class ApprovalSeeder extends Seeder
{
    public function run(): void
    {
        $reservations = Reservation::all();

        foreach ($reservations as $reservation) {
            if ($reservation->approvals()->count() < 2) {
                Approval::factory()->count(2 - $reservation->approvals()->count())->create([
                    'reservation_id' => $reservation->id
                ]);
            }
        }
    }
}
