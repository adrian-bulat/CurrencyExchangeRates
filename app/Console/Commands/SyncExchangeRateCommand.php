<?php

namespace App\Console\Commands;

use App\Enums\CurrencyISO;
use App\Interfaces\ExchangeRateUpdaterInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;

class SyncExchangeRateCommand extends Command
{
    protected $signature = 'exchange:sync';
    protected $description = 'Periodically requests currency exchange rates based on MDL';

    private const API_TIMEOUT = 30;
    private const RETRY_ATTEMPTS = 5;
    private const RETRY_DELAY = 5000;

    public ExchangeRateUpdaterInterface $exchangeRateUpdater;
    public function __construct(ExchangeRateUpdaterInterface $exchangeRateUpdater)
    {
        parent::__construct();

        $this->exchangeRateUpdater = $exchangeRateUpdater;
    }

    /**
     * Execute the console command.
     *
     * @throws \Exception
     */
    public function handle(): int
    {
        $exchangeRatesData = $this->fetchExchangeRates();

        if (!$exchangeRatesData) {
            return self::FAILURE;
        }

        $validatedRates = $this->validateRates($exchangeRatesData);

        if (empty($validatedRates['rates'])) {
            $this->error(
                sprintf(
                    'Command: %s - No valid exchange rates to process.',
                    __METHOD__,
                )
            );

            return self::FAILURE;
        }

        $this->exchangeRateUpdater->updateOrInsertExchangeRates($validatedRates);

        return self::SUCCESS;
    }

    /**
     * Fetch exchange rates from the external API.
     *
     * @throws \Exception
     */
    private function fetchExchangeRates(): ?array
    {
        try {
            $url = config('currency.api_url');
            $apiKey = config('currency.api_key');
            $baseCurrency = config('currency.base_currency');
            $supportedCurrencies = config('currency.supported_currencies');

            // TODO: Remove test url before push on GIT
            // $url = 'https://api.apilayer.com/exchangerates_data/2025-03-19';

            $httpResponse = retry(
                self::RETRY_ATTEMPTS,
                function () use ($apiKey, $url, $baseCurrency, $supportedCurrencies) {
                    return Http::timeout(self::API_TIMEOUT)
                        ->withHeaders(['apikey' => $apiKey])
                        ->get($url, [
                            'symbols' => implode('%2C', $supportedCurrencies),
                            'base' => $baseCurrency,
                        ]);
                },
                self::RETRY_DELAY,
            );
                info($httpResponse);
            if ($httpResponse->failed()) {
                $errorCode = $httpResponse->json()['error']['code'] ?? 'unknown';
                $this->error(
                    sprintf(
                        'Command: %s - Failed to fetch exchange rates. Error code: %s.',
                        __METHOD__,
                        $errorCode,
                    )
                );

                return null;
            }

            $exchangeRatesData = $httpResponse->json();

            if (!isset($exchangeRatesData['rates']) || !is_array($exchangeRatesData['rates'])) {
                $this->error(
                    sprintf(
                        'Command: %s - Invalid API response format. Response: %s',
                        __METHOD__,
                        $httpResponse,
                    )
                );

                return null;
            }

            return $exchangeRatesData;
        } catch (\Exception $exception) {
            $this->error("API request failed: " . $exception->getMessage());

            return null;
        }
    }


    private function validateRates(array $exchangeRatesData): ?array
    {
        $validatedRates = [];

        $validator = Validator::make([
            'base_currency' => $exchangeRatesData['base'],
            'published_date' => $exchangeRatesData['date'],
        ], [
            'base_currency' => ['required', 'string', new Enum(CurrencyISO::class)],
            'published_date' => ['required', 'date', 'date_format:Y-m-d'],
        ]);

        if ($validator->fails()) {
            $this->error(
                sprintf(
                    'Command: %s - Validation failed for base currency: %s date: %s. Error: %s',
                    __METHOD__,
                    $exchangeRatesData['base'],
                    $exchangeRatesData['date'],
                    json_encode($validator->errors()->all()),
                )
            );

            return [];
        }

        $validatedRates['base_currency'] = $exchangeRatesData['base'];
        $validatedRates['published_date'] = $exchangeRatesData['date'];

        foreach ($exchangeRatesData['rates'] as $currencyISO => $exchangeRate) {
        // TODO: Remove before push on GIT
            // $rateValidator =
            Validator::make([
                'target_currency' => $currencyISO,
                'rate' => $exchangeRate,
            ], [
                'target_currency' => ['required', 'string', new Enum(CurrencyISO::class)],
                'rate' => ['required', 'numeric', 'regex:/^\d{1,12}(\.\d{1,6})?$/', 'gt:0'],
            ]);

            if ($validator->fails()) {
                $this->error(
                    sprintf(
                        'Command: %s - Validation failed for currency: %s. Error: %s',
                        __METHOD__,
                        $currencyISO,
                        json_encode($validator->errors()->all()),
                    )
                );
                continue;
            }

            $validatedRates['rates'][$currencyISO] = $exchangeRate;
        }

        return $validatedRates;
    }
}
