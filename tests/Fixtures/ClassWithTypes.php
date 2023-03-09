<?php

declare(strict_types=1);

namespace WebFu\Tests\Fixtures;

class ClassWithTypes
{
    public int $simple;
    public int|string $union;

    public function returnVoid(): void
    {
    }
}
