<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExchangeRateSeeder extends Seeder
{
    public const BACKFILL_DAYS = 8;
    public const MAX_TARGET_CURRENCY_ID = 7;

    public function run(): void
    {
        $rates = [];

        /**
        * Make sure that array keys are same as ID's in currency_attribute table
        */
        $currencyRate = [
            1 => fn() => 1,
            2 => fn() => "19." . random_int(5,40),
            3 => fn() => "23." . random_int(20,30),
            4 => fn() => "3." . random_int(50,99),
            5 => fn() => "0." . random_int(20,30),
            6 => fn() => "0." . random_int(40,55),
            7 => fn() => "18." . random_int(0,50),
        ];

        for ($currentDay = 0; $currentDay < self::BACKFILL_DAYS; $currentDay++) {
            for ($targetCurrencyId = 2; $targetCurrencyId <= self::MAX_TARGET_CURRENCY_ID; $targetCurrencyId++) {
                $rates[] = [
                    'base_id' => 1,
                    'target_id' => $targetCurrencyId,
                    'rate' => $currencyRate[$targetCurrencyId](),
                    'published_date' => now()->subDays($currentDay),
                    'created_at' => now()->subDays($currentDay),
                    'updated_at' => null,
                ];
            }
        }

        DB::table('exchange_rate')->insert($rates);
    }
}
