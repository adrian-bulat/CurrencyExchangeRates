<?php

namespace App\Interfaces;

interface ExchangeRateUpdaterInterface
{
    /**
     * Method to update or insert exchange rates
     *
     * @param array $validatedRates
     * @return void
     */
    public function updateOrInsertExchangeRates(array $validatedRates): void;
}
