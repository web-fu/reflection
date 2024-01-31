<?php

namespace WebFu\Tests\Unit\Reflection;

use PHPUnit\Framework\TestCase;
use function WebFu\Reflection\reflection_type_names;

/**
 * @covers \WebFu\Reflection\reflection_type_names
 */
class ReflectionTypeNamesTest extends TestCase
{
    public function testReflectionTypeNames(): void
    {
        $reflectionClass = new \ReflectionClass('WebFu\Tests\Fixtures\ClassWithTypes');
        $reflectionProperty = $reflectionClass->getProperty('simple');
        $reflectionSimpleType = $reflectionProperty->getType();

        $this->assertSame(['int'], reflection_type_names($reflectionSimpleType));

        $reflectionProperty = $reflectionClass->getProperty('union');
        $reflectionUnionType = $reflectionProperty->getType();

        $this->assertSame(['int', 'string'], reflection_type_names($reflectionUnionType));

        $reflectionProperty = $reflectionClass->getProperty('noType');
        $reflectionNoType = $reflectionProperty->getType();

        $this->assertSame(['mixed'], reflection_type_names($reflectionNoType));

        $reflectionProperty = $reflectionClass->getProperty('nullable');
        $reflectionNullableType = $reflectionProperty->getType();

        $this->assertSame(['int', 'null'], reflection_type_names($reflectionNullableType));
    }
}