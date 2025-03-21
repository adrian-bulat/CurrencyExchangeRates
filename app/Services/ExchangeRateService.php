<?php

namespace App\Services;

use App\Interfaces\ExchangeRateUpdaterInterface;
use App\Models\ExchangeRate;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExchangeRateService implements ExchangeRateUpdaterInterface
{
    public const BASE = 1;
    public const DECIMALS = 2;

    public function __construct()
    {
    }
    public function getAllExchangeRates($perPage = 10)
    {
        return ExchangeRate::paginate($perPage);
    }

    public function filterExchangeRates($filters, $perPage = 10)
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

        return $query->paginate($perPage);
    }

    public function updateOrInsertExchangeRates(array $validatedRates): void
    {
        try {
            DB::beginTransaction();

            $currencies = array_unique(
                array_merge([$validatedRates['base_currency']], array_keys($validatedRates['rates']))
            );

            info("Processing exchange rates for currencies: " . implode(', ', $currencies));

            $currencyAttributes = self::getCurrencyId($currencies);

            $storeData = self::removeUnsupportedCurrencies($currencyAttributes, $currencies, $validatedRates);

            info("Currency attributes after removal: " . implode(', ', $currencyAttributes));

            foreach ($storeData['rates'] as $isoCurrency => $rate) {
                $baseCurrencyId = $currencyAttributes[$storeData['base_currency']];
                $convertedCurrency = round(self::BASE / $rate, self::DECIMALS);
                $targetCurrencyId = $currencyAttributes[$isoCurrency];
                $publishedDate = $storeData['published_date'];
// TODO: Thinking to move below part till the end to Model: ExchangeRate in a separate function
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
        array $validatedRates): array
    {
        foreach ($currencies as $currency) {
            if (!isset($currencyAttributes[$currency])) {
                Log::warning(
                    sprintf(
                        'Currency %s is missing from currency_attribute table. Currency %s hase rate: %f',
                        $currency,
                        $currency,
                        $validatedRates['rates'][$currency],
                    )
                );
                unset($validatedRates['rates'][$currency]);
            }
        }

        return $validatedRates;
    }
}
