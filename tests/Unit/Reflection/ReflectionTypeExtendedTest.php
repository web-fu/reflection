<?php

declare(strict_types=1);

namespace WebFu\Tests\Unit\Reflection;

use PHPUnit\Framework\TestCase;
use WebFu\Reflection\ReflectionTypeExtended;

class ReflectionTypeExtendedTest extends TestCase
{
    public function setUp(): void
    {
        require_once __DIR__ . '/../../Fixtures/example.php';
    }

    /**
     * @covers \WebFu\Reflection\ReflectionTypeExtended::getTypeNames
     */
    public function testGetTypeNames(): void
    {
        $reflectionType = new ReflectionTypeExtended(['string'], ['class-string']);

        $this->assertEquals(['string'], $reflectionType->getTypeNames());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionTypeExtended::getDocBlockTypeNames
     */
    public function testGetDocBlockTypeNames(): void
    {
        $reflectionType = new ReflectionTypeExtended(['string'], ['class-string']);

        $this->assertEquals(['class-string'], $reflectionType->getDocBlockTypeNames());
    }
}
