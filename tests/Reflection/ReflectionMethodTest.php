<?php

declare(strict_types=1);

namespace WebFu\Tests\Reflection;

use PHPUnit\Framework\TestCase;
use WebFu\Reflection\ReflectionMethod;
use WebFu\Tests\Fixtures\ClassWithDocComments;

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
}
