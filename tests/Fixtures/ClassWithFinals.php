<?php

namespace WebFu\Reflection\Tests\Fixtures;

class ClassWithFinals
{
    final public const PUBLIC_FINAL = 6;

    final public function finalMethod(): void
    {
    }
}