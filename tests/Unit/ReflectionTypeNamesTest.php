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

namespace WebFu\Reflection\Tests\Unit;

use PHPUnit\Framework\TestCase;
use ReflectionClass;

use function WebFu\Reflection\reflection_type_names;

/**
 * @covers \WebFu\Reflection\reflection_type_names
 */
class ReflectionTypeNamesTest extends TestCase
{
    public function testReflectionTypeNames(): void
    {
        $reflectionClass      = new ReflectionClass('WebFu\Reflection\Tests\Fixtures\ClassWithTypes');
        $reflectionProperty   = $reflectionClass->getProperty('simple');
        $reflectionSimpleType = $reflectionProperty->getType();

        $this->assertSame(['int'], reflection_type_names($reflectionSimpleType));

        $reflectionProperty  = $reflectionClass->getProperty('union');
        $reflectionUnionType = $reflectionProperty->getType();

        $this->assertSame(['int', 'string'], reflection_type_names($reflectionUnionType));

        $reflectionProperty = $reflectionClass->getProperty('noType');
        $reflectionNoType   = $reflectionProperty->getType();

        $this->assertSame(['mixed'], reflection_type_names($reflectionNoType));

        $reflectionProperty     = $reflectionClass->getProperty('nullable');
        $reflectionNullableType = $reflectionProperty->getType();

        $this->assertSame(['int', 'null'], reflection_type_names($reflectionNullableType));
    }
}
