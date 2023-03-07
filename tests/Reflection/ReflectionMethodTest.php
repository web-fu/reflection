<?php

declare(strict_types=1);

namespace WebFu\Tests\Reflection;

use PHPUnit\Framework\TestCase;
use WebFu\Reflection\ReflectionMethod;
use WebFu\Reflection\ReflectionType;
use WebFu\Tests\Fixtures\ClassWithDocComments;
use WebFu\Tests\Fixtures\ClassWithMethods;

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

    public function testGetReturnType(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals(new ReflectionType(['string']), $reflectionMethod->getReturnType());
    }
}
