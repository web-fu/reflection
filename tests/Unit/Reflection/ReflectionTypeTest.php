<?php

declare(strict_types=1);

namespace WebFu\Tests\Unit\Reflection;

use PHPUnit\Framework\TestCase;
use WebFu\Reflection\ReflectionType;

class ReflectionTypeTest extends TestCase
{
    public function testAllowNull(): void
    {
        $reflectionType = new ReflectionType(['string', 'null']);

        $this->assertTrue($reflectionType->allowNull());
    }

    public function testHasType(): void
    {
        $reflectionType = new ReflectionType(['string', 'null']);

        $this->assertTrue($reflectionType->hasType('string'));
        $this->assertFalse($reflectionType->hasType('int'));
    }

    public function testGetTypeNames(): void
    {
        $reflectionType = new ReflectionType(['string', 'null']);

        $this->assertEquals(['string', 'null'], $reflectionType->getTypeNames());
    }

    public function testToString(): void
    {
        $reflectionType = new ReflectionType(['string', 'null']);

        $this->assertEquals('string|null', $reflectionType->__toString());
    }
}