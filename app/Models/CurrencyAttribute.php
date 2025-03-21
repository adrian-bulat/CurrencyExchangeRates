<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyAttribute extends Model
{
    use HasFactory;

    protected $table = 'currency_attribute';
    protected $primaryKey = 'iso';
    protected $timestamp = false;
    protected $fillable = [
        'iso',
        'name',
        'code',
    ];

    public function baseCurrencyRates()
    {
        return $this->hasMany(ExchangeRate::class, 'base_id', 'id');
    }

    public function targetCurrencyRates()
    {
        return $this->hasMany(ExchangeRate::class, 'target_id', 'id');
    }
}
