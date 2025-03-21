<?php

namespace App\Interfaces;

interface ExchangeRateUpdaterInterface
{
    public function updateOrInsertExchangeRates(array $rates): void;
}
