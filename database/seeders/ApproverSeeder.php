<?php

namespace Database\Seeders;

use App\Models\Approver;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ApproverSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->count(15)->create();

        $users = User::where('role', 'approver')->pluck('id')->toArray();

        $faker = \Faker\Factory::create();

        foreach ($users as $user) {
            Approver::create([
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'title' => $faker->jobTitle(),
                'phone_number' => $faker->regexify('08\d{10}'),
                'user_id' => $user,
            ]);
        }
    }
}
