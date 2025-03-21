<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ExchangeRateService;
use Symfony\Component\HttpFoundation\Response;

class ExchangeRateController extends Controller
{
    protected ExchangeRateService $exchangeRateService;

    public function __construct(ExchangeRateService $exchangeRateService)
    {
        $this->exchangeRateService = $exchangeRateService;
    }

    public function index(): Response
    {
        return response()->file(public_path('exchangeRates.html'));
    }

    public function filter(Request $request): Response
    {
        $perPage = $request->input('perPage', 10);
        $filters = $request->only(['base', 'currency', 'date', 'date_from', 'date_to']);
        $filteredRates = $this->exchangeRateService->filterExchangeRates($filters, $perPage);

        return response()->json($filteredRates);
    }
}
