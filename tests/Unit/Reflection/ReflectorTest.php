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
use WebFu\Reflection\ReflectionUseStatement;
use WebFu\Reflection\Reflector;
use WebFu\Tests\Fixtures\ClassWithDocComments;
use WebFu\Tests\Fixtures\GenericClass;

/**
 * @coversNothing
 */
class ReflectorTest extends TestCase
{
    public function testCreateReflectionClassUseStatements(): void
    {
        $reflectionClassUseStatements = Reflector::createReflectionClassUseStatements(ClassWithDocComments::class);

        $this->assertEquals(new ReflectionUseStatement(GenericClass::class, 'GC'), $reflectionClassUseStatements[0]);
    }

    public function testTypeResolver(): void
    {
        $reflectionType = Reflector::typeResolver(ClassWithDocComments::class, 'GC');

        $this->assertEquals(GenericClass::class, $reflectionType?->getTypeNames()[0]);

        $reflectionType = Reflector::typeResolver(ClassWithDocComments::class, 'GenericClass');

        $this->assertEquals(GenericClass::class, $reflectionType?->getTypeNames()[0]);

        $reflectionType = Reflector::typeResolver(ClassWithDocComments::class, 'foo');

        $this->assertNull($reflectionType);
    }
}
