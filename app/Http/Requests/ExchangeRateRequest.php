<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\CurrencyCode;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class ExchangeRateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'base_currency' => ['required', 'string', new Enum(CurrencyCode::class)],
            'target_currency' => ['required', 'string', new Enum(CurrencyCode::class)],
            'exchange_rate' => ['required', 'numeric', 'max_digits:12', 'decimal:12,6', 'gt:0'],
            'created_at' => ['required', 'date', 'date_format:Y-m-d'],
        ];
    }
}
