<?php

declare(strict_types=1);

namespace WebFu\Tests\Unit\Reflection;

use WebFu\Reflection\ReflectionEnum;
use PHPUnit\Framework\TestCase;
use WebFu\Tests\Fixtures\BackedEnum;
use WebFu\Tests\Fixtures\BasicEnum;

class ReflectionEnumTest extends TestCase
{
    /**
     * @dataProvider backingTypeProvider
     * @param class-string $className
     * @param string[] $expected
     */
    public function testGetBackingType(string $className, array $expected): void
    {
        $reflectionEnum = new ReflectionEnum($className);

        $this->assertSame($expected, $reflectionEnum->getBackingType()->getTypeNames());
    }

    /**
     * @return iterable<array{className: class-string, expected: string[]}>
     */
    public function backingTypeProvider(): iterable
    {
        yield 'backed-enum' => [
            'className' => BackedEnum::class,
            'expected' => ['int'],
        ];
        yield 'basic-enum' => [
            'className' => BasicEnum::class,
            'expected' => ['mixed'],
        ];
    }

    /**
     * @dataProvider caseNameProvider
     */
    public function testGetCase(string $className, string $case, mixed $expected): void
    {
        $reflectionEnum = new ReflectionEnum($className);

        $this->assertSame($expected, $reflectionEnum->getCase($case)->getValue());
    }

    public function caseNameProvider(): iterable
    {
        yield 'one' => [
          'className' => BackedEnum::class,
          'case' => 'ONE',
          'expected' => BackedEnum::ONE,
        ];
    }

    public function testGetCases(): void
    {
        $this->markTestIncomplete();
    }

    public function testHasCases(): void
    {
        $this->markTestIncomplete();
    }

    public function testIsBacked(): void
    {
        $this->markTestIncomplete();
    }
}
