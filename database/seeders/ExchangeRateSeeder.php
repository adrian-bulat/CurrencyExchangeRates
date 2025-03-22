<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExchangeRateSeeder extends Seeder
{
    public function run(): void
    {
        $upToDaysAgo = 8;
        $maxTargetCurrencyId = 7;
        $rates = [];

        $array = [
            2 => fn() => "19." . random_int(5,40),
            3 => fn() => "23." . random_int(20,30),
            4 => fn() => "3." . random_int(50,99),
            5 => fn() => "0." . random_int(20,30),
            6 => fn() => "0." . random_int(40,55),
            7 => fn() => "18." . random_int(0,50),
        ];

        for ($currentDay = 0; $currentDay < $upToDaysAgo; $currentDay++) {
            for ($targetCurrencyId = 2; $targetCurrencyId <= $maxTargetCurrencyId; $targetCurrencyId++) {
                $rates[] = [
                    'base_id' => 1,
                    'target_id' => $targetCurrencyId,
                    'rate' => $array[$targetCurrencyId](),
                    'published_date' => now()->subDays($currentDay),
                    'created_at' => now()->subDays($currentDay),
                    'updated_at' => null,
                ];
            }
        }

        DB::table('exchange_rate')->insert($rates);
    }
}
