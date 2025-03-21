<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    use HasFactory;

    protected $table = 'exchange_rate';
    protected $fillable = [
        'base_currency',
        'target_currency',
        'exchange_rate',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
    ];
    //TODO: Delete before push on Git
/*
    private const BASE = 1;
    private const DECIMALS = 2;
    public static function updateOrInsert(array $validatedRates): void
    {
        try {
            DB::beginTransaction();

            $currencyList = array_unique(
                array_merge([$validatedRates['base_currency']], array_keys($validatedRates['rates']))
            );

            info("Processing exchange rates for currencies: " . implode(', ', $currencyList));

            $currencyMappings = CurrencyAttribute::whereIn('iso', $currencyList)
                ->pluck('id', 'iso')
                ->toArray();

            foreach ($currencyList as $currency) {
                if (!isset($currencyMappings[$currency])) {
                    warning(
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

            foreach ($validatedRates['rates'] as $targetCurrencyISO => $rate) {
                $baseCurrencyId = $currencyMappings[$validatedRates['base_currency']];
                $targetCurrencyId = $currencyMappings[$targetCurrencyISO];
                $publishedDate = $validatedRates['published_date'];

                $convertedCurrencyByRate = round(self::BASE / $rate, self::DECIMALS);

                Log::info(
                    sprintf(
                        "Updating exchange rate: Base %s -> Target %s, Rate: %.2f, Date: %s",
                        $validatedRates['base_currency'],
                        $targetCurrencyISO,
                        $convertedCurrencyByRate,
                        $publishedDate,
                    ));

                DB::table('exchange_rate')->updateOrInsert(
                    [
                        'base_id' => $baseCurrencyId,
                        'target_id' => $targetCurrencyId,
                        'published_date' => $publishedDate,
                    ],
                    [
                        'rate' => $convertedCurrencyByRate,
                        'updated_at' => now(),
                    ]
                );
            }

            DB::commit();
            info("Exchange rates updated successfully!");

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Transaction failed: " . $e->getMessage());
        }
    }
*/

    public function baseCurrency()
    {
        return $this->belongsTo(CurrencyAttribute::class, 'base_currency', 'id');
    }

    public function targetCurrency()
    {
        return $this->belongsTo(CurrencyAttribute::class, 'target_currency', 'id');
    }
}
