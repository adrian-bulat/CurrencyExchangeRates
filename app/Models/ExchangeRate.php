<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    use HasFactory;

    protected $table = 'exchange_rate';
    protected $fillable = [
        'base_id',
        'target_id',
        'rate',
        'published_date',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
    ];

    public function baseCurrency()
    {
        return $this->belongsTo(CurrencyAttribute::class, 'base_id', 'id');
    }

    public function targetCurrency()
    {
        return $this->belongsTo(CurrencyAttribute::class, 'target_id', 'id');
    }
}
