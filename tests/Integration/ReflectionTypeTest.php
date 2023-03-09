<?php

declare(strict_types=1);

namespace WebFu\Tests\Integration;

use PHPUnit\Framework\TestCase;
use WebFu\Reflection\ReflectionClass;
use WebFu\Tests\Fixtures\ClassWithTypes;

class ReflectionTypeTest extends TestCase
{
    public function testHasType(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithTypes::class);
        $this->assertTrue($reflectionClass->getProperty('simple')?->getType()->hasType('int'));
        $this->assertTrue($reflectionClass->getProperty('union')?->getType()->hasType('int'));
        $this->assertTrue($reflectionClass->getProperty('union')->getType()->hasType('string'));
        $this->assertTrue($reflectionClass->getMethod('returnVoid')->getReturnType()->hasType('void'));
    }
}
