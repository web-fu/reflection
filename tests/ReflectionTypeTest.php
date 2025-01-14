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
use WebFu\Reflection\ReflectionClass;
use WebFu\Reflection\ReflectionType;
use WebFu\Reflection\Tests\data\ClassWithDocComments;
use WebFu\Reflection\Tests\data\ClassWithIntersectionTypes;
use WebFu\Reflection\Tests\data\ClassWithTypes;
use WebFu\Reflection\WrongPhpVersionException;

/**
 * @group unit
 *
 * @coversDefaultClass  \WebFu\Reflection\ReflectionType
 */
class ReflectionTypeTest extends TestCase
{
    /**
     * @rcovers ::__construct
     */
    public function testReflectionType(): void
    {
        $reflectionType = new ReflectionType(['int', 'null']);

        $this->assertEquals(['int', 'null'], $reflectionType->getTypeNames());

        $reflectionType = new ReflectionType(['string'], ['non-empty-string']);

        $this->assertEquals(['string'], $reflectionType->getTypeNames());
        $this->assertEquals(['non-empty-string'], $reflectionType->getPhpDocTypeNames());
    }

    /**
     * @covers ::allowNull
     */
    public function testAllowNull(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithTypes::class);
        $reflectionType  = $reflectionClass->getProperty('nullable')->getType();

        $this->assertTrue($reflectionType->allowNull());
    }

    /**
     * @covers ::hasType
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

        $this->assertTrue($reflectionClass->getMethod('returnVoid')->getReturnType()->hasType('void'));

        if (PHP_VERSION_ID < 80100) {
            $this->markTestSkipped('hasType() cannot check intersection types for PHP versions lower than 8.1.0');
        }

        $reflectionClass = new ReflectionClass(ClassWithIntersectionTypes::class);
        $reflectionType  = $reflectionClass->getProperty('intersection')->getType();

        $this->assertTrue($reflectionType->hasType('Countable&Iterator'));
        $this->assertFalse($reflectionType->hasType('Countable'));
        $this->assertFalse($reflectionType->hasType('Iterator'));
    }

    /**
     * @covers ::getTypeNames
     */
    public function testGetTypeNames(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithTypes::class);
        $reflectionType  = $reflectionClass->getProperty('nullable')->getType();

        $this->assertEquals(['int', 'null'], $reflectionType->getTypeNames());
    }

    /**
     * @covers ::__toString
     */
    public function testToString(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithTypes::class);
        $reflectionType  = $reflectionClass->getProperty('nullable')->getType();

        $this->assertEquals('int|null', $reflectionType->__toString());
    }

    /**
     * @covers ::isUnionType
     */
    public function testIsUnionType(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithTypes::class);
        $reflectionType  = $reflectionClass->getProperty('union')->getType();

        $this->assertTrue($reflectionType->isUnionType());

        $reflectionType = $reflectionClass->getProperty('simple')->getType();
        $this->assertFalse($reflectionType->isUnionType());
    }

    /**
     * @covers ::isIntersectionType
     */
    public function testIsIntersectionType(): void
    {
        if (PHP_VERSION_ID < 80100) {
            $this->expectException(WrongPhpVersionException::class);
            $this->expectExceptionMessage('isIntersectionType() is not available for PHP versions lower than 8.1.0');

            $reflectionClass = new ReflectionClass(ClassWithTypes::class);
            $reflectionType  = $reflectionClass->getProperty('simple')->getType();
            $reflectionType->isIntersectionType();

            $this->markTestSkipped('isIntersectionType() is not available for PHP versions lower than 8.1.0');
        }

        $reflectionClass = new ReflectionClass(ClassWithIntersectionTypes::class);
        $reflectionType  = $reflectionClass->getProperty('intersection')->getType();

        $this->assertTrue($reflectionType->isIntersectionType());

        $reflectionClass = new ReflectionClass(ClassWithTypes::class);
        $reflectionType  = $reflectionClass->getProperty('simple')->getType();
        $this->assertFalse($reflectionType->isIntersectionType());
    }

    /**
     * @covers ::getPhpDocTypeNames
     */
    public function testGetPhpDocTypeNames(): void
    {
        $reflectionClass    = new ReflectionClass(ClassWithDocComments::class);
        $reflectionProperty = $reflectionClass->getProperty('property');

        $this->assertEquals(['class-string'], $reflectionProperty->getType()->getPhpDocTypeNames());

        $reflectionProperty = $reflectionClass->getProperty('noDocComments');
        $this->assertEquals([], $reflectionProperty->getType()->getPhpDocTypeNames());
    }

    /**
     * @covers ::__debugInfo
     */
    public function testDebugInfo(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithTypes::class);
        $reflectionType  = $reflectionClass->getProperty('nullable')->getType();

        $this->assertEquals(
            [
                'types'       => ['int', 'null'],
                'phpDocTypes' => [],
            ],
            $reflectionType->__debugInfo()
        );
    }
}
