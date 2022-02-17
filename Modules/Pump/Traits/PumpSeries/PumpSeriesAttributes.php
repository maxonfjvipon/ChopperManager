<?php

namespace Modules\Pump\Traits\PumpSeries;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrExploded;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Text\TxtImploded;

trait PumpSeriesAttributes
{
    // ATTRIBUTES
    /**
     * @throws Exception
     */
    public function getTempsMinAttribute(): array|bool
    {
        return $this->explodedAttribute('temps_min');
    }

    /**
     * @throws Exception
     */
    public function getTempsMaxAttribute(): array|bool
    {
        return $this->explodedAttribute('temps_max');
    }

    /**
     * @throws Exception
     */
    protected function explodedAttribute($originalKey, $separator = ","): array
    {
        return $this->original[$originalKey] !== null
            ? ArrMapped::new(
                ArrExploded::new(
                    $separator,
                    $this->original[$originalKey]
                ),
                'intval'
            )->asArray()
            : [];
    }

    /**
     * @throws Exception
     */
    private function implodedAttributes($attributes, $separator = ","): string
    {
        return TxtImploded::new(
            $separator,
            ...ArrMapped::new(
                [...$attributes],
                fn ($attribute) => $attribute->name
            )->asArray()
        )->asString();
    }

    /**
     * @throws Exception
     */
    public function getImplodedTypesAttribute(): string
    {
        return $this->implodedAttributes($this->types, ", ");
    }

    /**
     * @throws Exception
     */
    public function getImplodedApplicationsAttribute(): string
    {
        return $this->implodedAttributes($this->applications, ", ");
    }
}
