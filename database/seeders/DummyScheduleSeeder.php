<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;
use App\Models\TripSchedule;
use Carbon\Carbon;

class DummyScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = Package::all();

        foreach ($packages as $package) {
            // Create 5 schedules for each package over the next 6 months
            for ($i = 1; $i <= 5; $i++) {
                // Random start date between 1 week and 6 months from now
                $startDate = Carbon::now()->addWeeks($i * 4)->addDays(rand(0, 6));
                
                // End date based on package duration
                $endDate = (clone $startDate)->addDays($package->duration_days - 1);

                // Varied price (base price +/- 10%)
                $priceVariance = rand(-5, 10) / 100;
                $price = $package->base_price * (1 + $priceVariance);
                // Round to nearest 10
                $price = round($price / 10) * 10;

                TripSchedule::create([
                    'package_id' => $package->id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'price' => $price,
                    'quota' => $package->type == 'private' ? 1 : rand(10, 20),
                    'available_seats' => $package->type == 'private' ? 1 : rand(5, 15),
                    'status' => 'available',
                ]);
            }
        }
    }
}
