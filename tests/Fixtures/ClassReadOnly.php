<?php

namespace WebFu\Tests\Fixtures;

readonly class ClassReadOnly
{
    public function __construct(private int $value)
    {
    }

    public function getValue(): int
    {
        return $this->value;
    }
}