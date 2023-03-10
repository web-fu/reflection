<?php

declare(strict_types=1);

namespace WebFu\Tests\Unit\Reflection;

use PHPUnit\Framework\TestCase;
use WebFu\Reflection\ReflectionParameter;
use WebFu\Reflection\ReflectionType;
use WebFu\Reflection\ReflectionTypeExtended;
use WebFu\Tests\Fixtures\ClassWithDocComments;

class ReflectionParameterTest extends TestCase
{
    public function testGetAnnotation(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertEquals([
            '@param class-string $property',
        ], $reflectionParameter->getAnnotations());
    }

    public function testGetType(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertEquals(new ReflectionType(['string']), $reflectionParameter->getType());
    }

    public function testGetDocTypeName(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertEquals(['class-string'], $reflectionParameter->getDocTypeNames());

        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'noDocComments'], 'noDocComments');

        $this->assertEquals([], $reflectionParameter->getDocTypeNames());
    }

    public function testGetTypeExtended(): void
    {
        $reflectionParameter = new ReflectionParameter([ClassWithDocComments::class, 'setProperty'], 'property');

        $this->assertEquals(new ReflectionTypeExtended(['string'], ['class-string']), $reflectionParameter->getTypeExtended());
    }
}
