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
use ReflectionNamedType;
use ReflectionUnionType;

use function WebFu\Reflection\reflection_type_create;

use WebFu\Reflection\ReflectionType;

/**
 * @coversNothing
 */
class ReflectionTypeCreateTest extends TestCase
{
    /**
     * @covers \WebFu\Reflection\reflection_type_create
     *
     * @dataProvider typeProvider
     */
    public function testReflectionTypeCreate(ReflectionType $expected, \ReflectionType|ReflectionNamedType|ReflectionUnionType|null $reflectionType): void
    {
        $reflectionType = reflection_type_create($reflectionType);

        $this->assertEquals($expected, $reflectionType);

        $this->markTestIncomplete('This test must be completed');
    }

    public function typeProvider(): iterable
    {
        yield 'null' => [
            new ReflectionType(['mixed']),
            null,
        ];
    }
}
