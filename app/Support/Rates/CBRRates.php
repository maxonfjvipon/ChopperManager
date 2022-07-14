<?php

namespace App\Support\Rates;

use App\Interfaces\Rates;
use App\Models\Enums\Currency;
use Exception;
use JetBrains\PhpStorm\Pure;

/**
 * Rates of Central Bank of Russia.
 */
final class CBRRates implements Rates
{
    /**
     * @var Currency base
     */
    private Currency $base;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->base = Currency::fromValue(Currency::RUB);
    }

    /**
     * {@inheritDoc}
     */
    #[Pure]
 public function hasTheSameBaseAs(Currency|int $currency): bool
 {
     return $this->base->is($currency);
 }

    /**
     * {@inheritDoc}
     */
    public function rateFor(string $code): int|float
    {
        $xml = simplexml_load_string(file_get_contents('https://www.cbr.ru/scripts/XML_daily.asp'));
        $valutes = collect(json_decode(json_encode($xml))->Valute);
        $from = $this->isRub($code) ? 1 : ($valutes->firstWhere('CharCode', $code)->Value ?? 1);
        $to = $this->isRub($this->base->key) ? 1 : ($valutes->firstWhere('CharCode', $this->base->key)->Value ?? 1);

        return floatval(str_replace(',', '.', $to)) / floatval(str_replace(',', '.', $from));
    }

    private function isRub(string $code): bool
    {
        return $code === Currency::getKey(Currency::RUB);
    }
}
