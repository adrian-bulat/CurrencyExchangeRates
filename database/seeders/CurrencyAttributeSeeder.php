<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencyAttributeSeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            ['id' => 1, 'iso' => 'MDL', 'name' => 'Moldavian Leu', 'code' => 498],
            ['id' => 2, 'iso' => 'EUR', 'name' => 'Euro', 'code' => 978],
            ['id' => 3, 'iso' => 'GBP', 'name' => 'British Pound', 'code' => 826],
            ['id' => 4, 'iso' => 'RON', 'name' => 'Romanian Leu', 'code' => 946],
            ['id' => 5, 'iso' => 'RUB', 'name' => 'Russian ruble', 'code' => 643],
            ['id' => 6, 'iso' => 'UAH', 'name' => 'Ukrainian hryvnia', 'code' => 980],
            ['id' => 7, 'iso' => 'USD', 'name' => 'United States Dollar', 'code' => 840],
        ];

        DB::table('currency_attribute')->insert($currencies);
    }
}
