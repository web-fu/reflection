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

namespace WebFu\Reflection\Tests;

use PHPUnit\Framework\TestCase;
use WebFu\Reflection\ReflectionAttribute;
use WebFu\Reflection\ReflectionClass;
use WebFu\Reflection\Tests\data\ClassWithAttributes;

/**
 * @group unit
 *
 * @coversDefaultClass \WebFu\Reflection\ReflectionAttribute
 */
class ReflectionAttributeTest extends TestCase
{
    public function testGetTarget(): void
    {
        $class      = new ClassWithAttributes();
        $reflection = new ReflectionClass($class);

        $attributes = $reflection->getAttributes();
        $this->assertCount(1, $attributes);

        $attribute = $attributes[0];

        $this->assertEquals(ReflectionAttribute::TARGET_CLASS, $attribute->getTarget());
    }

    public function testGetName(): void
    {
        $class      = new ClassWithAttributes();
        $reflection = new ReflectionClass($class);

        $attributes = $reflection->getAttributes();
        $this->assertCount(1, $attributes);

        $attribute = $attributes[0];

        $this->assertEquals('WebFu\Reflection\Tests\data\Attribute', $attribute->getName());
    }

    public function testIsRepeated(): void
    {
        $class      = new ClassWithAttributes();
        $reflection = new ReflectionClass($class);

        $attributes = $reflection->getAttributes();
        $this->assertCount(1, $attributes);

        $attribute = $attributes[0];

        $this->assertFalse($attribute->isRepeated());
    }

    public function testGetArguments(): void
    {
        $class      = new ClassWithAttributes();
        $reflection = new ReflectionClass($class);

        $attributes = $reflection->getAttributes();
        $this->assertCount(1, $attributes);

        $attribute = $attributes[0];

        $this->assertEquals([], $attribute->getArguments());
    }
}
