<?php

namespace Qoraiche\Peak\Services\Widgets;

abstract class BaseWidget
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
