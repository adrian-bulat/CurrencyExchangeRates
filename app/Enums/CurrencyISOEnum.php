<?php

namespace App\Enums;

enum CurrencyISOEnum: string
{
    case AED = 'AED';
    case ALL = 'ALL';
    case AMD = 'AMD';
    case AUD = 'AUD';
    case AZN = 'AZN';
    case BGN = 'BGN';
    case BYN = 'BYN';
    case CAD = 'CAD';
    case CHF = 'CHF';
    case CNY = 'CNY';
    case CZK = 'CZK';
    case DKK = 'DKK';
    case EUR = 'EUR';
    case GBP = 'GBP';
    case GEL = 'GEL';
    case HKD = 'HKD';
    case HUF = 'HUF';
    case ILS = 'ILS';
    case INR = 'INR';
    case ISK = 'ISK';
    case JPY = 'JPY';
    case KGS = 'KGS';
    case KRW = 'KRW';
    case KWD = 'KWD';
    case KZT = 'KZT';
    case MDL = 'MDL';
    case MKD = 'MKD';
    case MYR = 'MYR';
    case NOK = 'NOK';
    case NZD = 'NZD';
    case PLN = 'PLN';
    case RON = 'RON';
    case RSD = 'RSD';
    case RUB = 'RUB';
    case SEK = 'SEK';
    case TJS = 'TJS';
    case TMT = 'TMT';
    case TRY = 'TRY';
    case UAH = 'UAH';
    case USD = 'USD';
    case UZS = 'UZS';
    case XDR = 'XDR';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
