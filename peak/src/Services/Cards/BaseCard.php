<?php

namespace Qoraiche\Peak\Services\Cards;

abstract class BaseCard
{
    /** @var mixed|array */
    protected mixed $services;

    /**
     * @param ...$services
     */
    public function __construct(...$services)
    {
        $this->services = $services;
    }

    /**
     * @return array
     */
    abstract public function data(): array;

    /**
     * @return string
     */
    public function getComponent(): string
    {
        return class_basename(static::class); // Extracts the Vue component name
    }
}
