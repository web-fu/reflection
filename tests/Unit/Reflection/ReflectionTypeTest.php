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

namespace WebFu\Tests\Unit\Reflection;

use PHPUnit\Framework\TestCase;
use WebFu\Reflection\ReflectionClass;
use WebFu\Reflection\ReflectionType;
use WebFu\Reflection\WrongPhpVersionException;
use WebFu\Tests\Fixtures\ClassWithIntersectionTypes;
use WebFu\Tests\Fixtures\ClassWithTypes;

/**
 * @covers \WebFu\Reflection\ReflectionType
 */
class ReflectionTypeTest extends TestCase
{
    /**
     * @covers \WebFu\Reflection\ReflectionType::allowNull
     */
    public function testAllowNull(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithTypes::class);
        $reflectionType  = $reflectionClass->getProperty('nullable')->getType();

        $this->assertTrue($reflectionType->allowNull());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionType::hasType
     */
    public function testHasType(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithTypes::class);

        $simpleType = $reflectionClass->getProperty('simple')->getType();

        $this->assertFalse($simpleType->hasType('string'));
        $this->assertTrue($simpleType->hasType('int'));

        $unionType = $reflectionClass->getProperty('union')->getType();

        $this->assertTrue($unionType->hasType('int'));
        $this->assertTrue($unionType->hasType('string'));

        if (PHP_VERSION_ID < 80100) {
            $this->markTestSkipped('hasType() cannot check intersection types for PHP versions lower than 8.1.0');
        }

        $reflectionClass = new ReflectionClass(ClassWithIntersectionTypes::class);
        $reflectionType  = $reflectionClass->getProperty('intersection')->getType();

        $this->assertTrue($reflectionType->hasType('\Countable&\Iterator'));
        $this->assertFalse($reflectionType->hasType('\Countable'));
        $this->assertFalse($reflectionType->hasType('\Iterator'));
    }

    /**
     * @covers \WebFu\Reflection\ReflectionType::getTypeNames
     */
    public function testGetTypeNames(): void
    {
        $reflectionType = new ReflectionType(['string', 'null']);

        $this->assertEquals(['null', 'string'], $reflectionType->getTypeNames());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionType::__toString
     */
    public function testToString(): void
    {
        $reflectionType = new ReflectionType(['string', 'null']);

        $this->assertEquals('null|string', $reflectionType->__toString());
    }

    public function testIsUnionType(): void
    {
        $reflectionType = new ReflectionType(['string', 'null']);

        $this->assertTrue($reflectionType->isUnionType());
    }

    public function testIsIntersectionType(): void
    {
        if (PHP_VERSION_ID < 80100) {
            $this->expectException(WrongPhpVersionException::class);
            $this->expectExceptionMessage('isIntersectionType() is not available for PHP versions lower than 8.1.0');

            $reflectionType = new ReflectionType(['string', 'null']);
            $reflectionType->isIntersectionType();

            $this->markTestSkipped('isIntersectionType() is not available for PHP versions lower than 8.1.0');
        }

        $reflectionType = new ReflectionType(['string', 'null'], '&');

        $this->assertTrue($reflectionType->isIntersectionType());

        $reflectionType = new ReflectionType(['string', 'null']);
        $this->assertTrue($reflectionType->isIntersectionType());
    }
}
