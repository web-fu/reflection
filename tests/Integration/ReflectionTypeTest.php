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

namespace WebFu\Reflection\Tests\Integration;

use PHPUnit\Framework\TestCase;
use WebFu\Reflection\ReflectionClass;
use WebFu\Reflection\Tests\Fixtures\ClassWithTypes;

/**
 * @group integration
 *
 * @coversDefaultClass \WebFu\Reflection\ReflectionType
 */
class ReflectionTypeTest extends TestCase
{
    /**
     * @covers ::hasType
     */
    public function testHasType(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithTypes::class);
        $this->assertTrue($reflectionClass->getProperty('simple')?->getType()->hasType('int'));
        $this->assertTrue($reflectionClass->getProperty('union')?->getType()->hasType('int'));
        $this->assertTrue($reflectionClass->getProperty('union')->getType()->hasType('string'));
        $this->assertTrue($reflectionClass->getMethod('returnVoid')->getReturnType()->hasType('void'));
    }
}
