<?php

namespace App\Console\Commands;

use App\Enums\CurrencyCode;
use App\Models\ExchangeRates;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;

class SyncExchangeRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exchange:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Periodically requests currency exchange rates based on MDL';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $url = config('currency.api_url');
        $apiKey = config('currency.api_key');
        $baseCurrency = config('currency.base_currency');;
        $supportedCurrencies = config('currency.supported_currencies');

        $response = Http::timeout(10)
            ->withHeaders(['apikey' => $apiKey])
            ->get($url, [
                'symbols' => implode('%2C', $supportedCurrencies),
                'base' => $baseCurrency,
            ]);

        $this->info($response);

        if ($response->failed()) {
            $this->error('Failed to fetch exchange rates. ' . $response->json()['error']['code']);
            return;
        }

        $response = $response->json();

        if (!isset($response['rates']) || !is_array($response['rates'])) {
            $this->error('Invalid response structure.');
            return;
        }

        $validRates = $this->validateRates($response);

        if (empty($validRates)) {
            $this->error('No valid exchange rates to process.');
            return;
        }

        ExchangeRates::updateOrCreate($validRates);
    }

    private function validateRates(array $response): array
    {
        $validatedRates = [];

        $data = [
                'base_currency' => $response['base'],
                'created_at' => $response['date'],
        ];

        $validator = Validator::make($data, [
                'base_currency' => ['required', 'string', new Enum(CurrencyCode::class)],
                'created_at' => ['required', 'date', 'date_format:Y-m-d'],
        ]);

        if ($validator->fails()) {
            $this->error(
                'Validation failed for: '
                . $response['base'] . ' date: ' . $response['date']
                . json_encode($validator->errors()->all())
            );
            exit;
        }

        $validatedRates['base_currency'] = $response['base'];
        $validatedRates['created_at'] = $response['date'];

        foreach ($response['rates'] as $currencyCode => $exchangeRate) {

            $data = [
                'target_currency' => $currencyCode,
                'exchange_rate' => $exchangeRate,
            ];

            $validator = Validator::make($data, [
                'target_currency' => ['required', 'string', new Enum(CurrencyCode::class)],
                'exchange_rate' => ['required', 'numeric', 'regex:/^\d{1,12}(\.\d{1,6})?$/', 'gt:0'],
            ]);

            if ($validator->fails()) {
                $this->error(
                    'Validation failed for '
                    . $currencyCode . ': '
                    . json_encode($validator->errors()->all())
                );
                continue;
            }

            $validatedRates['rates'][$currencyCode] = $exchangeRate;
        }

        return $validatedRates;
    }
}
