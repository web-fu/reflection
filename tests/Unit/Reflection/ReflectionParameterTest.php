<?php

declare(strict_types=1);

namespace WebFu\Tests\Unit\Reflection;

use PHPUnit\Framework\TestCase;
use WebFu\Reflection\ReflectionParameter;
use WebFu\Reflection\ReflectionType;
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
        $this->assertEquals('class-string', $reflectionParameter->getDocTypeName());
    }
}
