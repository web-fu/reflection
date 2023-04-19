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

    public function testAllowsNull(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertFalse($reflectionParameter->allowsNull());
    }

    public function testCanBePassedByValue(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertTrue($reflectionParameter->canBePassedByValue());
    }

    public function testGetAnnotation(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertEquals([
            '@param class-string $property',
        ], $reflectionParameter->getAnnotations());
    }

    public function testGetAttributes(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertEquals([], $reflectionParameter->getAttributes());
    }

    public function testGetDeclaringClass(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertEquals(new ReflectionClass(ClassWithDocComments::class), $reflectionParameter->getDeclaringClass());

        $reflectionParameter = new ReflectionParameter('example', 'param');

        $this->assertNull($reflectionParameter->getDeclaringClass());
    }

    public function getDefaultValue(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithMethods::class, 'methodWithAllDefaultParameters'], 'param1');

        $this->assertEquals(1, $reflectionParameter->getDefaultValue());
    }

    public function testGetDeclaringFunction(): void
    {
        $reflectionParameter = new ReflectionParameter('example', 'param');

        $this->assertEquals(new ReflectionFunction('example'), $reflectionParameter->getDeclaringFunction());

        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertEquals(new ReflectionMethod(ClassWithDocComments::class, 'setProperty'), $reflectionParameter->getDeclaringFunction());
    }

    public function testGetType(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertEquals(new ReflectionType(['string']), $reflectionParameter->getType());
    }

    public function testGetTypeNames(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithTypes::class, 'methodWithTypedParam'], 'string');

        $this->assertEquals(['string'], $reflectionParameter->getTypeNames());

        $reflectionParameter = new ReflectionParameter([ClassWithTypes::class, 'methodWithoutTypedParam'], 'param');

        $this->assertEquals(['mixed'], $reflectionParameter->getTypeNames());
    }

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

    public function testGetTypeExtended(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertEquals(new ReflectionTypeExtended(['string'], ['class-string']), $reflectionParameter->getTypeExtended());
    }

    public function testGetDefaultValue(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithMethods::class, 'methodWithAllDefaultParameters'], 'param1');

        $this->assertEquals(1, $reflectionParameter->getDefaultValue());
    }

    public function testGetDefaultValueConstantName(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithMethods::class, 'methodWithAllDefaultParameters'], 'param1');

        $this->assertEquals('self::PARAM1', $reflectionParameter->getDefaultValueConstantName());
    }

    public function testGetPosition(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertEquals(0, $reflectionParameter->getPosition());
    }

    public function testHasType(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertTrue($reflectionParameter->hasType());
    }

    public function testIsDefaultValueAvailable(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithMethods::class, 'methodWithAllDefaultParameters'], 'param1');

        $this->assertTrue($reflectionParameter->isDefaultValueAvailable());
    }

    public function testIsDefaultValueConstant(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithMethods::class, 'methodWithAllDefaultParameters'], 'param1');

        $this->assertTrue($reflectionParameter->isDefaultValueConstant());
    }

    public function testIsOptional(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithMethods::class, 'methodWithAllDefaultParameters'], 'param1');

        $this->assertTrue($reflectionParameter->isOptional());
    }

    public function testIsPassedByReference(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertFalse($reflectionParameter->isPassedByReference());
    }

    public function testIsVariadic(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertFalse($reflectionParameter->isVariadic());
    }
}
