<?php

declare(strict_types=1);

namespace WebFu\Tests\Fixtures;

class ClassNonClonable
{
    private function __clone()
    {
    }
}