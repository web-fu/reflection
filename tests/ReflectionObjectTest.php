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
use WebFu\Reflection\ReflectionObject;
use WebFu\Reflection\Tests\data\GenericClass;

/**
 * @group unit
 *
 * @coversDefaultClass  \WebFu\Reflection\ReflectionObject
 */
class ReflectionObjectTest extends TestCase
{
    /**
     * @dataProvider objectProvider
     *
     * @covers ::__construct
     */
    public function testReflectionObject(object $object): void
    {
        $reflectionObject = new ReflectionObject($object);

        $this->assertInstanceOf(ReflectionObject::class, $reflectionObject);
    }

    public function objectProvider(): iterable
    {
        yield 'anonymous-class' => [
            'object' => new class() {
                public function foo(): string
                {
                    return 'foo';
                }
            },
        ];

        yield 'generic' => [
            'object' => new GenericClass(),
        ];
    }
}
