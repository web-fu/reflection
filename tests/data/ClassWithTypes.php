<?php

declare(strict_types=1);

namespace WebFu\Reflection\Tests\data;

class ClassWithTypes
{
    public int $simple;
    public int|string $union;
    public $noType;
    public ?int $nullable;

    public function methodWithTypedParam(string $string): void
    {
    }

    public function methodWithoutTypedParam($param): void
    {
    }

    public function returnVoid(): void
    {
    }
}
