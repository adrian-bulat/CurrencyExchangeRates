<?php

namespace App\Services;

use App\Interfaces\ExchangeRateServiceInterface;
use App\Interfaces\ExchangeRateUpdaterInterface;
use App\Models\ExchangeRate;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\JsonResponse;

class ExchangeRateService implements ExchangeRateUpdaterInterface, ExchangeRateServiceInterface
{
    public const BASE_EXCHANGE_VALUE = 1;
    public const DECIMALS = 2;

    public function __construct()
    {
    }

    public function filterExchangeRates(array $filters, int $perPage = 10): JsonResponse
    {
        $query = ExchangeRate::query()
            ->select(
                'base_currency.iso as base_currency',
                'target_currency.iso as target_currency',
                'exchange_rate.rate',
                'exchange_rate.published_date'
            )
            ->join(
                'currency_attribute as base_currency',
                'exchange_rate.base_id',
                '=',
                'base_currency.id'
            )
            ->join(
                'currency_attribute as target_currency',
                'exchange_rate.target_id',
                '=',
                'target_currency.id'
            );

        if (!empty($filters['currency'])) {
            $currencies = explode(',', $filters['currency']);
            $query->whereIn('target_currency.iso', $currencies);
        }

        if (!empty($filters['date'])) {
            $query->whereDate('exchange_rate.published_date', '=', $filters['date']);
        } elseif (!empty($filters['date_from']) && !empty($filters['date_to'])) {
            $query->whereBetween('exchange_rate.published_date', [$filters['date_from'], $filters['date_to']]);
        } elseif (!empty($filters['date_from'])) {
            $query->where('exchange_rate.published_date', '>=', $filters['date_from']);
        }

        $query->orderBy('exchange_rate.published_date', 'ASC');

        return DataTables::of($query)->make(true);
    }

    public function updateOrInsertExchangeRates(array $rates): void
    {
        try {
            DB::beginTransaction();

            $currencies = array_unique(
                array_merge([$rates['base_currency']], array_keys($rates['rates']))
            );

            info("Processing exchange rates for currencies: " . implode(', ', $currencies));

            $currencyAttributes = self::getCurrencyId($currencies);

            $storeData = self::removeUnsupportedCurrencies($currencyAttributes, $currencies, $rates);

            info("Currency attributes after removal: " . implode(', ', $currencyAttributes));

            foreach ($storeData['rates'] as $isoCurrency => $rate) {
                $baseCurrencyId = $currencyAttributes[$storeData['base_currency']];
                $convertedCurrency = round(self::BASE_EXCHANGE_VALUE / $rate, self::DECIMALS);
                $targetCurrencyId = $currencyAttributes[$isoCurrency];
                $publishedDate = $storeData['published_date'];

                DB::table('exchange_rate')->updateOrInsert(
                    [
                        'base_id' => $baseCurrencyId,
                        'target_id' => $targetCurrencyId,
                        'published_date' => $publishedDate,
                    ],
                    [
                        'rate' => $convertedCurrency,
                        'updated_at' => now(),
                    ]
                );
            }

            DB::commit();

            Log::info('Exchange rates updated successfully!');
        } catch (Exception $e) {
            DB::rollBack();

            Log::error("Transaction failed: " . $e->getMessage());
        }
    }

    /**
     * Fetch the currency attributes (id and iso code) for a list of currencies.
     *
     * @param array $currencies
     * @return array
     */
    private static function getCurrencyId(array $currencies): array
    {
        return DB::table('currency_attribute')
            ->whereIn('iso', $currencies)
            ->pluck('id','iso')
            ->toArray();
    }

    private static function removeUnsupportedCurrencies(
        array $currencyAttributes,
        array $currencies,
        array $rates): array
    {
        foreach ($currencies as $currency) {
            if (!isset($currencyAttributes[$currency])) {
                Log::warning(
                    sprintf(
                        'Currency %s is missing from currency_attribute table. Currency %s hase rate: %f',
                        $currency,
                        $currency,
                        $rates['rates'][$currency],
                    )
                );
                unset($rates['rates'][$currency]);
            }
        }

        return $rates;
    }
}
