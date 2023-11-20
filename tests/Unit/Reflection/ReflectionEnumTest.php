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
        $reflectionEnum = new ReflectionEnum(BackedEnum::class);
        $actual = $reflectionEnum->getCases();

        $this->assertCount(1, $actual);
        $this->assertEquals(new \ReflectionEnumBackedCase(BackedEnum::class, 'ONE'), $actual[0]);
    }

    public function testHasCase(): void
    {
        $reflectionEnum = new ReflectionEnum(BackedEnum::class);

        $this->assertTrue($reflectionEnum->hasCase('ONE'));
        $this->assertFalse($reflectionEnum->hasCase('TWO'));
    }

    public function testIsBacked(): void
    {
        $backedEnum = new ReflectionEnum(BackedEnum::class);
        $basicEnum = new ReflectionEnum(BasicEnum::class);

        $this->assertTrue($backedEnum->isBacked());
        $this->assertFalse($basicEnum->isBacked());
    }
}
