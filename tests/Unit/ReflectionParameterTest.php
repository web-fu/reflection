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

namespace WebFu\Reflection\Tests\Unit;

use DateTime;
use PHPUnit\Framework\TestCase;
use WebFu\Reflection\ReflectionClass;
use WebFu\Reflection\ReflectionFunction;
use WebFu\Reflection\ReflectionMethod;
use WebFu\Reflection\ReflectionParameter;
use WebFu\Reflection\ReflectionType;
use WebFu\Reflection\Tests\Fixtures\BasicEnum;
use WebFu\Reflection\Tests\Fixtures\ClassWithComplexTypes;
use WebFu\Reflection\Tests\Fixtures\ClassWithDocComments;
use WebFu\Reflection\Tests\Fixtures\ClassWithMethods;
use WebFu\Reflection\Tests\Fixtures\ClassWithTypes;
use WebFu\Reflection\Tests\Fixtures\GenericClass;
use WebFu\Reflection\WrongPhpVersionException;

/**
 * @group unit
 *
 * @coversDefaultClass  \WebFu\Reflection\ReflectionParameter
 */
class ReflectionParameterTest extends TestCase
{
    protected function setUp(): void
    {
        require_once __DIR__.'/../Fixtures/example.php';
    }

    /**
     * @covers ::allowsNull
     */
    public function testAllowsNull(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertFalse($reflectionParameter->allowsNull());
    }

    /**
     * @covers ::canBePassedByValue
     */
    public function testCanBePassedByValue(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertTrue($reflectionParameter->canBePassedByValue());
    }

    /**
     * @covers ::getAnnotations
     */
    public function testGetAnnotation(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertEquals([
            '@param class-string $property',
        ], $reflectionParameter->getAnnotations());
    }

    /**
     * @covers ::getAttributes
     */
    public function testGetAttributes(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertEquals([], $reflectionParameter->getAttributes());
    }

    /**
     * @covers ::getDeclaringClass
     */
    public function testGetDeclaringClass(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertEquals(new ReflectionClass(ClassWithDocComments::class), $reflectionParameter->getDeclaringClass());

        $reflectionParameter = new ReflectionParameter('example', 'param');

        $this->assertNull($reflectionParameter->getDeclaringClass());
    }

    /**
     * @covers ::getDefaultValue
     */
    public function getDefaultValue(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithMethods::class, 'methodWithAllDefaultParameters'], 'param1');

        $this->assertEquals(1, $reflectionParameter->getDefaultValue());
    }

    /**
     * @covers ::getDeclaringFunction
     */
    public function testGetDeclaringFunction(): void
    {
        $reflectionParameter = new ReflectionParameter('example', 'param');

        $this->assertEquals(new ReflectionFunction('example'), $reflectionParameter->getDeclaringFunction());

        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertEquals(new ReflectionMethod(ClassWithDocComments::class, 'setProperty'), $reflectionParameter->getDeclaringFunction());
    }

    /**
     * @covers ::getType
     */
    public function testGetType(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertEquals(new ReflectionType(types: ['string'], phpDocTypeNames: ['class-string']), $reflectionParameter->getType());

        $reflectionParameter = new ReflectionParameter([ClassWithComplexTypes::class, 'setDateTime'], 'dateTime');

        $this->assertEquals(new ReflectionType(types: [DateTime::class]), $reflectionParameter->getType());

        if (PHP_VERSION_ID < 80100) {
            $this->expectException(WrongPhpVersionException::class);
            $this->expectExceptionMessage('isEnum() is not available for PHP versions lower than 8.1.0');

            $reflectionParameter = new ReflectionParameter([ClassWithComplexTypes::class, 'setBasicEnum'], 'basicEnum');
            $reflectionParameter->getType();

            $this->markTestSkipped('Enum are not available for PHP versions lower than 8.1.0');
        }

        $reflectionParameter = new ReflectionParameter([ClassWithComplexTypes::class, 'setBasicEnum'], 'basicEnum');
        $this->assertEquals(new ReflectionType(types: [BasicEnum::class]), $reflectionParameter->getType());
    }

    /**
     * @covers ::getTypeNames
     */
    public function testGetTypeNames(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithTypes::class, 'methodWithTypedParam'], 'string');

        $this->assertEquals(['string'], $reflectionParameter->getTypeNames());

        $reflectionParameter = new ReflectionParameter([ClassWithTypes::class, 'methodWithoutTypedParam'], 'param');

        $this->assertEquals(['mixed'], $reflectionParameter->getTypeNames());
    }

    /**
     * @covers ::getPhpDocTypeNames
     */
    public function testGetDocTypeNames(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertEquals(['class-string'], $reflectionParameter->getPhpDocTypeNames());

        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'noDocComments'], 'noDocComments');

        $this->assertEquals([], $reflectionParameter->getPhpDocTypeNames());

        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setUseStatementDocComment'], 'param');

        $this->assertEquals([GenericClass::class.'[]'], $reflectionParameter->getPhpDocTypeNames());

        $reflectionParameter = new ReflectionParameter('example', 'param');

        $this->assertEquals(['class-string'], $reflectionParameter->getPhpDocTypeNames());
    }

    /**
     * @covers ::getDefaultValue
     */
    public function testGetDefaultValue(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithMethods::class, 'methodWithAllDefaultParameters'], 'param1');

        $this->assertEquals(1, $reflectionParameter->getDefaultValue());
    }

    /**
     * @covers ::getDefaultValueConstantName
     */
    public function testGetDefaultValueConstantName(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithMethods::class, 'methodWithAllDefaultParameters'], 'param1');

        $this->assertEquals('self::PARAM1', $reflectionParameter->getDefaultValueConstantName());
    }

    /**
     * @covers ::getName
     */
    public function testGetPosition(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertEquals(0, $reflectionParameter->getPosition());
    }

    /**
     * @covers ::hasType
     */
    public function testHasType(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertTrue($reflectionParameter->hasType());
    }

    /**
     * @covers ::isDefaultValueAvailable
     */
    public function testIsDefaultValueAvailable(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithMethods::class, 'methodWithAllDefaultParameters'], 'param1');

        $this->assertTrue($reflectionParameter->isDefaultValueAvailable());
    }

    /**
     * @covers ::isDefaultValueConstant
     */
    public function testIsDefaultValueConstant(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithMethods::class, 'methodWithAllDefaultParameters'], 'param1');

        $this->assertTrue($reflectionParameter->isDefaultValueConstant());
    }

    /**
     * @covers ::isOptional
     */
    public function testIsOptional(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithMethods::class, 'methodWithAllDefaultParameters'], 'param1');

        $this->assertTrue($reflectionParameter->isOptional());
    }

    /**
     * @covers ::isPassedByReference
     */
    public function testIsPassedByReference(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertFalse($reflectionParameter->isPassedByReference());
    }

    /**
     * @covers ::isVariadic
     */
    public function testIsVariadic(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertFalse($reflectionParameter->isVariadic());
    }

    /**
     * @covers ::getAnnotations
     */
    public function testGetAnnotations(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertEquals([
            '@param class-string $property',
        ], $reflectionParameter->getAnnotations());
    }

    /**
     * @covers ::__debugInfo
     */
    public function testDebugInfo(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertEquals([
            'name'        => 'property',
            'class'       => ClassWithDocComments::class,
            'annotations' => [
                '@param class-string $property',
            ],
            'attributes' => [],
            'function'   => 'setProperty',
        ], $reflectionParameter->__debugInfo());
    }

    /**
     * @covers ::__toString
     */
    public function testToString(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertEquals('Parameter #0 [ <required> string $property ]', (string) $reflectionParameter);
    }
}
