<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = config('currency.currencies', []);

        foreach ($currencies as $code => $currency) {
            \App\Models\Currency::updateOrCreate(
                ['code' => $code],
                [
                    'name' => $currency['name'],
                    'symbol' => $currency['symbol'],
                    'rate' => $currency['rate'],
                    'format' => $currency['format'],
                    'is_active' => true,
                ]
            );
        }
    }
}
