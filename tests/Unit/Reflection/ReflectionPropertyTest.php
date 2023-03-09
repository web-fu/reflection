<?php

declare(strict_types=1);

namespace WebFu\Tests\Unit\Reflection;

use PHPUnit\Framework\TestCase;
use WebFu\Reflection\ReflectionProperty;
use WebFu\Reflection\ReflectionType;
use WebFu\Tests\Fixtures\ClassWithDocComments;

class ReflectionPropertyTest extends TestCase
{
    public function testGetAnnotation(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithDocComments::class, 'property');

        $this->assertEquals([
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
        $this->assertEquals('class-string', $reflectionProperty->getDocTypeName());
    }
}
