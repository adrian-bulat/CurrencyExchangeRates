<?php

namespace App\Http\Controllers;

use App\Interfaces\ExchangeRateServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Exceptions\Exception;

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

    public function dataTable(Request $request)
    {
        return view('table');
    }

    /**
     * @throws Exception
     */
    public function filter(Request $request): Response
    {
        $perPage = $request->input('perPage', 10);
        $filters = $request->only(['base', 'currency', 'date', 'date_from', 'date_to']);
        $filteredRates = $this->exchangeRateService->filterExchangeRates($filters, $perPage);
Log::info('resp; '. json_encode($filteredRates, JSON_THROW_ON_ERROR));
//        return response()->json($filteredRates);
        return $filteredRates;
    }
}
