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
use WebFu\Reflection\Tests\data\ClassWithDocComments;
use WebFu\Reflection\Tests\data\GenericClass;
use function WebFu\Reflection\reflection_type_resolver;

/**
 * @group unit
 *
 * @covers \WebFu\Reflection\reflection_type_resolver
 */
class ReflectionTypeResolverTest extends TestCase
{
    public function testReflectionTypeResolver(): void
    {
        $reflectionType = reflection_type_resolver(ClassWithDocComments::class, 'GC');

        $this->assertEquals(GenericClass::class, $reflectionType?->getTypeNames()[0]);

        $reflectionType = reflection_type_resolver(ClassWithDocComments::class, 'GenericClass');

        $this->assertEquals(GenericClass::class, $reflectionType?->getTypeNames()[0]);

        $reflectionType = reflection_type_resolver(ClassWithDocComments::class, 'foo');

        $this->assertNull($reflectionType);
    }
}
