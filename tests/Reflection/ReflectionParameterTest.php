<?php

declare(strict_types=1);

namespace WebFu\Tests\Reflection;

use PHPUnit\Framework\TestCase;
use WebFu\Reflection\ReflectionParameter;
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
}
