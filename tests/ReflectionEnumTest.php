<?php

declare(strict_types=1);

/**
 * This file is part of web-fu/reflection
 *
 * @copyright Web-Fu <info@web-fu.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WebFu\Reflection\Tests;

use PHPUnit\Framework\TestCase;
use ReflectionEnumBackedCase;
use WebFu\Reflection\ReflectionEnum;
use WebFu\Reflection\Tests\data\BackedEnum;
use WebFu\Reflection\Tests\data\BasicEnum;
use WebFu\Reflection\WrongPhpVersionException;

/**
 * @group unit
 *
 * @coversDefaultClass  \WebFu\Reflection\ReflectionEnum
 */
class ReflectionEnumTest extends TestCase
{
    /**
     * @covers ::getType
     *
     * @dataProvider backingTypeProvider
     *
     * @param class-string $className
     * @param string[]     $expected
     */
    public function testGetBackingType(string $className, array $expected): void
    {
        if (PHP_VERSION_ID < 80100) {
            $this->expectException(WrongPhpVersionException::class);
            $this->expectExceptionMessage('Enums are not available for PHP versions lower than 8.1.0');

            new ReflectionEnum($className);
            self::markTestSkipped('Enums are not available for PHP versions lower than 8.1.0');
        }

        $reflectionEnum = new ReflectionEnum($className);

        $this->assertSame($expected, $reflectionEnum->getType()->getTypeNames());
    }

    /**
     * @return iterable<array{className: class-string, expected: string[]}>
     */
    public function backingTypeProvider(): iterable
    {
        yield 'backed-enum' => [
            'className' => BackedEnum::class,
            'expected'  => ['int'],
        ];
        yield 'basic-enum' => [
            'className' => BasicEnum::class,
            'expected'  => ['mixed'],
        ];
    }

    /**
     * @covers ::getCase
     */
    public function testGetCase(): void
    {
        if (PHP_VERSION_ID < 80100) {
            self::markTestSkipped('Enums are not available for PHP versions lower than 8.1.0');
        }

        $reflectionEnum = new ReflectionEnum(BackedEnum::class);
        $this->assertEquals(new ReflectionEnumBackedCase(BackedEnum::class, 'ONE'), $reflectionEnum->getCase('ONE'));
    }

    /**
     * @covers ::getCases
     */
    public function testGetCases(): void
    {
        if (PHP_VERSION_ID < 80100) {
            self::markTestSkipped('Enums are not available for PHP versions lower than 8.1.0');
        }

        $reflectionEnum = new ReflectionEnum(BackedEnum::class);
        $actual         = $reflectionEnum->getCases();

        $this->assertCount(1, $actual);
        $this->assertEquals(new ReflectionEnumBackedCase(BackedEnum::class, 'ONE'), $actual[0]);
    }

    /**
     * @covers ::hasCase
     */
    public function testHasCase(): void
    {
        if (PHP_VERSION_ID < 80100) {
            self::markTestSkipped('Enums are not available for PHP versions lower than 8.1.0');
        }

        $reflectionEnum = new ReflectionEnum(BackedEnum::class);

        $this->assertTrue($reflectionEnum->hasCase('ONE'));
        $this->assertFalse($reflectionEnum->hasCase('TWO'));
    }

    /**
     * @covers ::isBacked
     */
    public function testIsBacked(): void
    {
        if (PHP_VERSION_ID < 80100) {
            self::markTestSkipped('Enums are not available for PHP versions lower than 8.1.0');
        }

        $backedEnum = new ReflectionEnum(BackedEnum::class);
        $basicEnum  = new ReflectionEnum(BasicEnum::class);

        $this->assertTrue($backedEnum->isBacked());
        $this->assertFalse($basicEnum->isBacked());
    }

    /**
     * @covers ::__debugInfo
     */
    public function testDebugInfo(): void
    {
        if (PHP_VERSION_ID < 80100) {
            self::markTestSkipped('Enums are not available for PHP versions lower than 8.1.0');
        }

        $reflectionEnum = new ReflectionEnum(BackedEnum::class);

        $this->assertSame([
            'name'        => BackedEnum::class,
            'attributes'  => [],
            'annotations' => [],
        ], $reflectionEnum->__debugInfo());
    }
}
