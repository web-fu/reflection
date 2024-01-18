<?php

declare(strict_types=1);

namespace WebFu\Tests\Unit\Reflection;

use PHPUnit\Framework\TestCase;
use WebFu\Reflection\ReflectionClass;
use WebFu\Reflection\ReflectionFunction;
use WebFu\Reflection\ReflectionMethod;
use WebFu\Reflection\ReflectionParameter;
use WebFu\Reflection\ReflectionType;
use WebFu\Reflection\ReflectionTypeExtended;
use WebFu\Tests\Fixtures\ClassWithDocComments;
use WebFu\Tests\Fixtures\ClassWithMethods;
use WebFu\Tests\Fixtures\ClassWithTypes;
use WebFu\Tests\Fixtures\GenericClass;

class ReflectionParameterTest extends TestCase
{
    public function setUp(): void
    {
        require_once __DIR__ . '/../../Fixtures/example.php';
    }

    /**
     * @covers \WebFu\Reflection\ReflectionParameter::allowsNull
     */
    public function testAllowsNull(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertFalse($reflectionParameter->allowsNull());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionParameter::canBePassedByValue
     */
    public function testCanBePassedByValue(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertTrue($reflectionParameter->canBePassedByValue());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionParameter::getAnnotations
     */
    public function testGetAnnotation(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertEquals([
            '@param class-string $property',
        ], $reflectionParameter->getAnnotations());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionParameter::getAttributes
     */
    public function testGetAttributes(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertEquals([], $reflectionParameter->getAttributes());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionParameter::getDeclaringClass
     */
    public function testGetDeclaringClass(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertEquals(new ReflectionClass(ClassWithDocComments::class), $reflectionParameter->getDeclaringClass());

        $reflectionParameter = new ReflectionParameter('example', 'param');

        $this->assertNull($reflectionParameter->getDeclaringClass());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionParameter::getDefaultValue
     */
    public function getDefaultValue(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithMethods::class, 'methodWithAllDefaultParameters'], 'param1');

        $this->assertEquals(1, $reflectionParameter->getDefaultValue());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionParameter::getDeclaringFunction
     */
    public function testGetDeclaringFunction(): void
    {
        $reflectionParameter = new ReflectionParameter('example', 'param');

        $this->assertEquals(new ReflectionFunction('example'), $reflectionParameter->getDeclaringFunction());

        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertEquals(new ReflectionMethod(ClassWithDocComments::class, 'setProperty'), $reflectionParameter->getDeclaringFunction());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionParameter::getType
     */
    public function testGetType(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertEquals(new ReflectionType(['string']), $reflectionParameter->getType());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionParameter::getTypeNames
     */
    public function testGetTypeNames(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithTypes::class, 'methodWithTypedParam'], 'string');

        $this->assertEquals(['string'], $reflectionParameter->getTypeNames());

        $reflectionParameter = new ReflectionParameter([ClassWithTypes::class, 'methodWithoutTypedParam'], 'param');

        $this->assertEquals(['mixed'], $reflectionParameter->getTypeNames());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionParameter::getDocTypeNames
     */
    public function testGetDocTypeNames(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertEquals(['class-string'], $reflectionParameter->getDocTypeNames());

        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'noDocComments'], 'noDocComments');

        $this->assertEquals([], $reflectionParameter->getDocTypeNames());

        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setUseStatementDocComment'], 'param');

        $this->assertEquals([GenericClass::class . '[]'], $reflectionParameter->getDocTypeNames());

        $reflectionParameter = new ReflectionParameter('example', 'param');

        $this->assertEquals(['class-string'], $reflectionParameter->getDocTypeNames());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionParameter::getTypeExtended
     */
    public function testGetTypeExtended(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertEquals(new ReflectionTypeExtended(['string'], ['class-string']), $reflectionParameter->getTypeExtended());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionParameter::getDefaultValue
     */
    public function testGetDefaultValue(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithMethods::class, 'methodWithAllDefaultParameters'], 'param1');

        $this->assertEquals(1, $reflectionParameter->getDefaultValue());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionParameter::getDefaultValueConstantName
     */
    public function testGetDefaultValueConstantName(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithMethods::class, 'methodWithAllDefaultParameters'], 'param1');

        $this->assertEquals('self::PARAM1', $reflectionParameter->getDefaultValueConstantName());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionParameter::getName
     */
    public function testGetPosition(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertEquals(0, $reflectionParameter->getPosition());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionParameter::hasType
     */
    public function testHasType(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertTrue($reflectionParameter->hasType());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionParameter::isDefaultValueAvailable
     */
    public function testIsDefaultValueAvailable(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithMethods::class, 'methodWithAllDefaultParameters'], 'param1');

        $this->assertTrue($reflectionParameter->isDefaultValueAvailable());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionParameter::isDefaultValueConstant
     */
    public function testIsDefaultValueConstant(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithMethods::class, 'methodWithAllDefaultParameters'], 'param1');

        $this->assertTrue($reflectionParameter->isDefaultValueConstant());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionParameter::isOptional
     */
    public function testIsOptional(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithMethods::class, 'methodWithAllDefaultParameters'], 'param1');

        $this->assertTrue($reflectionParameter->isOptional());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionParameter::isPassedByReference
     */
    public function testIsPassedByReference(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertFalse($reflectionParameter->isPassedByReference());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionParameter::isVariadic
     */
    public function testIsVariadic(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertFalse($reflectionParameter->isVariadic());
    }
}
