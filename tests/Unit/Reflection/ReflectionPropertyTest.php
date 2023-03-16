<?php

declare(strict_types=1);

namespace WebFu\Tests\Unit\Reflection;

use PHPUnit\Framework\TestCase;
use WebFu\Reflection\ReflectionProperty;
use WebFu\Reflection\ReflectionType;
use WebFu\Reflection\ReflectionTypeExtended;
use WebFu\Tests\Fixtures\ClassWithDocComments;
use WebFu\Tests\Fixtures\GenericClass;

class ReflectionPropertyTest extends TestCase
{
    public function testGetAnnotation(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithDocComments::class, 'property');

        $this->assertEquals([
            '@depends-annotations Test',
            '@var class-string',
        ], $reflectionProperty->getAnnotations());
    }

    public function testGetType(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithDocComments::class, 'property');

        $this->assertEquals(new ReflectionType(['string']), $reflectionProperty->getType());
    }

    public function testGetDocTypeName(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithDocComments::class, 'property');

        $this->assertEquals(['class-string'], $reflectionProperty->getDocTypeNames());

        $reflectionProperty = new ReflectionProperty(ClassWithDocComments::class, 'noDocComments');

        $this->assertEquals([], $reflectionProperty->getDocTypeNames());

        $reflectionProperty = new ReflectionProperty(ClassWithDocComments::class, 'useStatementDocComment');

        $this->assertEquals([GenericClass::class . '[]'], $reflectionProperty->getDocTypeNames());
    }

    public function testGetTypeExtended(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithDocComments::class, 'property');

        $this->assertEquals(new ReflectionTypeExtended(['string'], ['class-string']), $reflectionProperty->getTypeExtended());
    }
}
