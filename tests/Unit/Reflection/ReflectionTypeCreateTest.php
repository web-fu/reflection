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
use ReflectionClass;

use function WebFu\Reflection\reflection_type_create;

use WebFu\Reflection\ReflectionType;
use WebFu\Tests\Fixtures\ClassWithTypes;

/**
 * @covers \WebFu\Reflection\reflection_type_create
 */
class ReflectionTypeCreateTest extends TestCase
{
    /**
     * @covers \WebFu\Reflection\reflection_type_create
     */
    public function testReflectionTypeCreate(): void
    {
        // null
        $reflectionType = reflection_type_create(null);

        $this->assertEquals(new ReflectionType(['mixed']), $reflectionType);

        // ReflectionNamedType
        $reflectionClass    = new ReflectionClass(ClassWithTypes::class);
        $reflectionProperty = $reflectionClass->getProperty('simple');

        $reflectionType = reflection_type_create($reflectionProperty->getType());

        $this->assertEquals(new ReflectionType(['int']), $reflectionType);

        // ReflectionUnionType
        $reflectionClass    = new ReflectionClass(ClassWithTypes::class);
        $reflectionProperty = $reflectionClass->getProperty('union');

        $reflectionType = reflection_type_create($reflectionProperty->getType());

        $this->assertEquals(new ReflectionType(['int', 'string']), $reflectionType);
    }
}
