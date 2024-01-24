<?php

declare(strict_types=1);

/**
 * This file is part of web-fu/reflection
 *
 * @copyright Web-Fu <info@web-fu.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WebFu\Tests\Unit\Reflection;

use PHPUnit\Framework\TestCase;
use WebFu\Reflection\ReflectionType;

/**
 * @covers \WebFu\Reflection\ReflectionType
 */
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
