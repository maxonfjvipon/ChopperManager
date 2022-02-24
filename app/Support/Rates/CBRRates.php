<?php

namespace App\Support\Rates;

use Exception;
use Illuminate\Support\Facades\Auth;
use Modules\Project\Entities\Currency;

/**
 * Rates of Central Bank of Russia
 */
final class CBRRates implements Rates
{
    /**
     * @var string base
     */
    private string $base;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->base = Currency::allOrCached()->find(Auth::user()->currency_id)->code;
    }

    /**
     * @inheritDoc
     */
    public function hasTheSameBaseAs(Currency $currency): bool
    {
        return $this->base === $currency->code;
    }

    /**
     * @inheritDoc
     */
    public function rateFor(string $code): mixed
    {
        $xml = simplexml_load_string(file_get_contents("https://www.cbr.ru/scripts/XML_daily.asp"));
        $valutes = collect(json_decode(json_encode($xml))->Valute);
        $from = $this->isRub($code) ? 1 : ($valutes->firstWhere("CharCode", $code)->Value ?? 1);
        $to = $this->isRub($this->base) ? 1 : ($valutes->firstWhere("CharCode", $this->base)->Value ?? 1);

        return floatval(str_replace(",", ".", $to)) / floatval(str_replace(",", ".", $from));
    }

    /**
     * @param string $code
     * @return bool
     */
    private function isRub(string $code): bool
    {
        return $code === "RUB";
    }
}
