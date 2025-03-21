<?php

namespace App\Http\Controllers;

use App\Interfaces\ExchangeRateServiceInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExchangeRateController extends Controller
{
    public function __construct(
        private readonly ExchangeRateServiceInterface $exchangeRateService
    ) {
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
