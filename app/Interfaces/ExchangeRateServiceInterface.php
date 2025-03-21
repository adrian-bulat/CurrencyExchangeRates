<?php

namespace App\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ExchangeRateServiceInterface
{
    public function filterExchangeRates(array $filters, int $perPage = 10): LengthAwarePaginator;
}
