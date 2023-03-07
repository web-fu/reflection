<?php

declare(strict_types=1);

namespace WebFu\Tests\Reflection;

use PHPUnit\Framework\TestCase;
use WebFu\Reflection\ReflectionProperty;
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
}
