<?php

declare(strict_types=1);

namespace WebFu\Reflection\Tests\data;

class ClassNonClonable
{
    private function __clone()
    {
    }
}
