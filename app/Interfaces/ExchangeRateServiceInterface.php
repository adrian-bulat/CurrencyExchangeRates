<?php

namespace App\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;


interface ExchangeRateServiceInterface
{
    public function filterExchangeRates(array $filters, int $perPage = 10): JsonResponse;
}
