<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExchangeRates extends Model
{
    use HasFactory;

    protected $table = 'currency_rates';
    protected $fillable = [
        'base_currency',
        'target_currency',
        'exchange_rate',
        'created_at',
        'updated_at',
    ];

    public static function updateOrCreate(array $ratesToStore): void
    {
        try {
            DB::beginTransaction();

            foreach ($ratesToStore['rates'] as $currency => $rate) {
                DB::table('currency_rates')->updateOrInsert([
                    'base_currency' => $ratesToStore['base_currency'],
                    'target_currency' => $currency,
                    'created_at' => $ratesToStore['created_at'],
                ], [
                    'exchange_rate' => $rate,
                    'updated_at' => now(),
                ]);
            }

            DB::commit();
            Log::info('Exchange rates updated successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Transaction failed: " . $e->getMessage());
        }
    }
}
