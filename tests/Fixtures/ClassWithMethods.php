<?php

declare(strict_types=1);

namespace WebFu\Tests\Fixtures;

class ClassWithMethods
{
    public function methodWithoutParameters(): void
    {
    }

    public function methodWithAllMandatoryParameters(int $param1, string $param2): void
    {
    }

    public function methodWithAllDefaultParameters(int $param1 = 1, string $param2 = 'string'): void
    {
    }

    public function methodWithSomeDefaultParameters(int $param1, string $param2 = 'string'): void
    {
    }
}
