<?php

declare(strict_types=1);

namespace WebFu\Reflection\Tests\data;

class ClassWithMethods
{
    public const PARAM1 = 1;

    public function __construct(int $param1 = 1, string $param2 = 'string')
    {
    }

    public function methodWithoutParameters(): void
    {
    }

    public function methodWithAllMandatoryParameters(int $param1, string $param2): array
    {
        return [
            'param1' => $param1,
            'param2' => $param2,
        ];
    }

    public function methodWithAllDefaultParameters(int $param1 = self::PARAM1, string $param2 = 'string'): void
    {
    }

    public function methodWithSomeDefaultParameters(int $param1, string $param2 = 'string'): void
    {
    }

    protected function protectedMethod(): void
    {
    }

    private function privateMethod(): void
    {
    }

    public static function staticMethod(): void
    {
    }

    public function __destruct()
    {
    }
}
