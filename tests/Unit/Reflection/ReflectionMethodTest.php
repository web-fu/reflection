<?php

declare(strict_types=1);

namespace WebFu\Tests\Unit\Reflection;

use PHPUnit\Framework\TestCase;
use WebFu\Reflection\ReflectionMethod;
use WebFu\Reflection\ReflectionType;
use WebFu\Reflection\ReflectionTypeExtended;
use WebFu\Tests\Fixtures\ClassWithDocComments;
use WebFu\Tests\Fixtures\ClassWithMethods;
use WebFu\Tests\Fixtures\GenericClass;

class ReflectionMethodTest extends TestCase
{
    public function testGetAnnotation(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals([
            '@depends-annotations Test',
            '@return class-string',
        ], $reflectionMethod->getAnnotations());
    }

    public function testGetNumberOfParameters(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters');
        $this->assertEquals(0, $reflectionMethod->getNumberOfParameters());

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithAllMandatoryParameters');
        $this->assertEquals(2, $reflectionMethod->getNumberOfParameters());

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithAllDefaultParameters');
        $this->assertEquals(2, $reflectionMethod->getNumberOfParameters());

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithSomeDefaultParameters');
        $this->assertEquals(2, $reflectionMethod->getNumberOfParameters());
    }

    public function testGetNumberOfRequiredParameters(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters');
        $this->assertEquals(0, $reflectionMethod->getNumberOfRequiredParameters());

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithAllMandatoryParameters');
        $this->assertEquals(2, $reflectionMethod->getNumberOfRequiredParameters());

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithAllDefaultParameters');
        $this->assertEquals(0, $reflectionMethod->getNumberOfRequiredParameters());

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithSomeDefaultParameters');
        $this->assertEquals(1, $reflectionMethod->getNumberOfRequiredParameters());
    }

    public function testGetParameters(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithSomeDefaultParameters');
        $parameters = $reflectionMethod->getParameters();

        $this->assertEquals(new ReflectionType(['int']), $parameters[0]->getType());
        $this->assertEquals(new ReflectionType(['string']), $parameters[1]->getType());
    }

    public function testGetReturnType(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals(new ReflectionType(['string']), $reflectionMethod->getReturnType());
    }

    public function testGetReturnDocTypeName(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals(['class-string'], $reflectionMethod->getReturnDocTypeNames());

        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'noDocComments');

        $this->assertEquals([], $reflectionMethod->getReturnDocTypeNames());

        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getUseStatementDocComment');

        $this->assertEquals([GenericClass::class . '[]'], $reflectionMethod->getReturnDocTypeNames());
    }

    public function testGetReturnTypeExtended(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals(new ReflectionTypeExtended(['string'], ['class-string']), $reflectionMethod->getReturnTypeExtended());
    }
}
