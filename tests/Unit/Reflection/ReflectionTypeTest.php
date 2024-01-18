<?php

declare(strict_types=1);

namespace WebFu\Tests\Unit\Reflection;

use PHPUnit\Framework\TestCase;
use WebFu\Reflection\ReflectionType;

class ReflectionTypeTest extends TestCase
{
    /**
     * @covers \WebFu\Reflection\ReflectionType::allowNull
     */
    public function testAllowNull(): void
    {
        $reflectionType = new ReflectionType(['string', 'null']);

        $this->assertTrue($reflectionType->allowNull());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionType::hasType
     */
    public function testHasType(): void
    {
        $reflectionType = new ReflectionType(['string', 'null']);

        $this->assertTrue($reflectionType->hasType('string'));
        $this->assertFalse($reflectionType->hasType('int'));
    }

    /**
     * @covers \WebFu\Reflection\ReflectionType::getTypeNames
     */
    public function testGetTypeNames(): void
    {
        $reflectionType = new ReflectionType(['string', 'null']);

        $this->assertEquals(['string', 'null'], $reflectionType->getTypeNames());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionType::__toString
     */
    public function testToString(): void
    {
        $reflectionType = new ReflectionType(['string', 'null']);

        $this->assertEquals('string|null', $reflectionType->__toString());
    }
}
