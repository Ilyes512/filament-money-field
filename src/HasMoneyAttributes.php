<?php

namespace Pelmered\FilamentMoneyField;

use Filament\Infolists\Infolist;
use Filament\Support\Concerns\EvaluatesClosures;
use Money\Currencies\ISOCurrencies;
use Money\Currency;

/**
 * @mixin EvaluatesClosures
 */
trait HasMoneyAttributes
{
    protected Currency $currency;
    protected string $locale;
    protected ?string $monetarySeparator = null;

    protected function getCurrency(): Currency
    {
        return $this->currency ?? $this->currency(config('filament-money-field.default_currency') ?? Infolist::$defaultCurrency)->getCurrency();
    }

    protected function getLocale(): string
    {
        return $this->locale ?? config('filament-money-field.default_locale');
    }

    public function currency(string|\Closure|null $currencyCode = null): static
    {
        $currencyCode = $this->evaluate($currencyCode);

        $this->currency = new Currency($currencyCode);
        $currencies = new ISOCurrencies();

        if (!$currencies->contains($this->currency)) {
            throw new \RuntimeException('Currency not supported: ' . $currencyCode);
        }

        return $this;
    }

    public function locale(string|\Closure|null $locale = null): static
    {
        $this->locale = $this->evaluate($locale);

        return $this;
    }
}
